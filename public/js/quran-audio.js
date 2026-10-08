/**
 * Quran Mazid - Global Audio Engine
 * High-performance audio streaming with Ayah synchronization
 */

class QuranAudioPlayer {
    constructor() {
        this.audio = new Audio();
        this.isPlaying = false;
        this.currentSurahId = 1;
        this.currentAyahNumber = 1;
        this.totalAyahs = 7;
        this.currentReciter = "Alafasy_128kbps";
        this.currentReciterName = "Mishary Rashid Alafasy";
        this.loopMode = "continuous"; // 'off', 'repeat_ayah', 'repeat_surah', 'continuous'
        this.playbackSpeed = 1.0;
        this.speedOptions = [0.75, 1.0, 1.25, 1.5, 2.0];

        this.init();
    }

    init() {
        // Load saved state
        const savedSurah = localStorage.getItem("quran_last_surah");
        const savedAyah = localStorage.getItem("quran_last_ayah");
        const savedReciter = localStorage.getItem("quran_reciter");
        const savedReciterName = localStorage.getItem("quran_reciter_name");

        if (savedSurah) this.currentSurahId = parseInt(savedSurah);
        if (savedAyah) this.currentAyahNumber = parseInt(savedAyah);
        if (savedReciter) this.currentReciter = savedReciter;
        if (savedReciterName) this.currentReciterName = savedReciterName;

        this.bindEvents();
        this.updateUI();
    }

    bindEvents() {
        this.audio.addEventListener("timeupdate", () => this.onTimeUpdate());
        this.audio.addEventListener("ended", () => this.onTrackEnded());
        this.audio.addEventListener("play", () => {
            this.isPlaying = true;
            this.updatePlayPauseButtons();
        });
        this.audio.addEventListener("pause", () => {
            this.isPlaying = false;
            this.updatePlayPauseButtons();
        });
        this.audio.addEventListener("error", (e) => {
            console.warn("Audio playback error:", e);
            // Fallback to alternative CDN or next ayah
        });
    }

    getAyahAudioUrl(surah, ayah, reciter = this.currentReciter) {
        const s = String(surah).padStart(3, "0");
        const a = String(ayah).padStart(3, "0");
        return `https://everyayah.com/data/${reciter}/${s}${a}.mp3`;
    }

    playAyah(surahId, ayahNumber, totalVerses = null) {
        this.currentSurahId = parseInt(surahId);
        this.currentAyahNumber = parseInt(ayahNumber);

        if (totalVerses) {
            this.totalAyahs = totalVerses;
        } else if (window.QURAN_DATA) {
            const s = window.QURAN_DATA.surahs.find(item => item.id === this.currentSurahId);
            if (s) this.totalAyahs = s.verses;
        }

        const url = this.getAyahAudioUrl(this.currentSurahId, this.currentAyahNumber);
        this.audio.src = url;
        this.audio.playbackRate = this.playbackSpeed;
        
        this.audio.play().then(() => {
            this.isPlaying = true;
            this.saveState();
            this.updateUI();
            this.highlightActiveAyah();
        }).catch(err => {
            console.log("Audio play prevented or loading:", err);
        });
    }

    playSurah(surahId, startAyah = 1) {
        this.playAyah(surahId, startAyah);
    }

    togglePlayPause() {
        if (!this.audio.src || this.audio.src === "") {
            this.playAyah(this.currentSurahId, this.currentAyahNumber);
            return;
        }

        if (this.isPlaying) {
            this.audio.pause();
        } else {
            this.audio.play();
        }
    }

    nextAyah() {
        if (this.currentAyahNumber < this.totalAyahs) {
            this.playAyah(this.currentSurahId, this.currentAyahNumber + 1);
        } else if (this.currentSurahId < 114) {
            // Next surah
            this.currentSurahId++;
            this.playAyah(this.currentSurahId, 1);
            if (window.location.pathname.includes('/surah/')) {
                const baseUrl = window.APP_BASE_URL || '';
                window.location.href = `${baseUrl}/surah/${this.currentSurahId}`;
            }
        }
    }

    prevAyah() {
        if (this.currentAyahNumber > 1) {
            this.playAyah(this.currentSurahId, this.currentAyahNumber - 1);
        } else if (this.currentSurahId > 1) {
            this.currentSurahId--;
            this.playAyah(this.currentSurahId, 1);
            if (window.location.pathname.includes('/surah/')) {
                const baseUrl = window.APP_BASE_URL || '';
                window.location.href = `${baseUrl}/surah/${this.currentSurahId}`;
            }
        }
    }

    onTrackEnded() {
        if (this.loopMode === "repeat_ayah") {
            this.audio.currentTime = 0;
            this.audio.play();
        } else if (this.loopMode === "repeat_surah") {
            if (this.currentAyahNumber < this.totalAyahs) {
                this.nextAyah();
            } else {
                this.playAyah(this.currentSurahId, 1);
            }
        } else if (this.loopMode === "continuous") {
            this.nextAyah();
        }
    }

    toggleLoopMode() {
        const modes = ["continuous", "repeat_ayah", "repeat_surah", "off"];
        const idx = modes.indexOf(this.loopMode);
        this.loopMode = modes[(idx + 1) % modes.length];
        
        const loopBtn = document.getElementById("audioLoopBtn");
        if (loopBtn) {
            if (this.loopMode === "repeat_ayah") {
                loopBtn.innerHTML = '<i class="fa-solid fa-repeat"></i> <span style="font-size:9px;">1</span>';
                loopBtn.classList.add("active");
            } else if (this.loopMode === "repeat_surah") {
                loopBtn.innerHTML = '<i class="fa-solid fa-repeat"></i>';
                loopBtn.classList.add("active");
            } else if (this.loopMode === "continuous") {
                loopBtn.innerHTML = '<i class="fa-solid fa-forward-step"></i>';
                loopBtn.classList.add("active");
            } else {
                loopBtn.innerHTML = '<i class="fa-solid fa-repeat"></i>';
                loopBtn.classList.remove("active");
            }
        }
    }

    cycleSpeed() {
        const idx = this.speedOptions.indexOf(this.playbackSpeed);
        this.playbackSpeed = this.speedOptions[(idx + 1) % this.speedOptions.length];
        this.audio.playbackRate = this.playbackSpeed;

        const speedBtn = document.getElementById("audioSpeedBtn");
        if (speedBtn) {
            speedBtn.textContent = `${this.playbackSpeed}x`;
        }
    }

    setReciter(reciterSubfolder, reciterName) {
        this.currentReciter = reciterSubfolder;
        this.currentReciterName = reciterName;
        localStorage.setItem("quran_reciter", reciterSubfolder);
        localStorage.setItem("quran_reciter_name", reciterName);

        if (this.isPlaying) {
            const curTime = this.audio.currentTime;
            this.playAyah(this.currentSurahId, this.currentAyahNumber);
        } else {
            this.updateUI();
        }
    }

    seekTo(fraction) {
        if (this.audio.duration) {
            this.audio.currentTime = fraction * this.audio.duration;
        }
    }

    setVolume(volume) {
        this.audio.volume = Math.max(0, Math.min(1, volume));
    }

    onTimeUpdate() {
        const cur = this.audio.currentTime || 0;
        const dur = this.audio.duration || 0;

        const curLabel = document.getElementById("audioCurTime");
        const durLabel = document.getElementById("audioDurTime");
        const seekSlider = document.getElementById("audioSeekSlider");

        if (curLabel) curLabel.textContent = this.formatTime(cur);
        if (durLabel && dur) durLabel.textContent = this.formatTime(dur);
        if (seekSlider && dur) {
            seekSlider.value = (cur / dur) * 100;
        }
    }

    formatTime(seconds) {
        const m = Math.floor(seconds / 60);
        const s = Math.floor(seconds % 60);
        return `${m}:${s < 10 ? '0' : ''}${s}`;
    }

    saveState() {
        localStorage.setItem("quran_last_surah", this.currentSurahId);
        localStorage.setItem("quran_last_ayah", this.currentAyahNumber);
    }

    highlightActiveAyah() {
        // Remove old active
        document.querySelectorAll(".ayah-card.active-playing").forEach(el => {
            el.classList.remove("active-playing");
        });

        // Add to active
        const target = document.getElementById(`ayah-${this.currentAyahNumber}`);
        if (target) {
            target.classList.add("active-playing");
            target.scrollIntoView({ behavior: "smooth", block: "center" });
        }
    }

    updateUI() {
        let surahName = `Surah ${this.currentSurahId}`;
        let banglaName = `সূরা ${this.currentSurahId}`;

        if (window.QURAN_DATA) {
            const s = window.QURAN_DATA.surahs.find(item => item.id === this.currentSurahId);
            if (s) {
                surahName = s.name;
                banglaName = s.bangla;
            }
        }

        const nameEl = document.getElementById("audioSurahName");
        const reciterEl = document.getElementById("audioReciterName");
        const bar = document.getElementById("globalAudioBar");

        if (nameEl) nameEl.textContent = `${banglaName} (${this.currentAyahNumber})`;
        if (reciterEl) reciterEl.textContent = this.currentReciterName;
        if (bar) bar.classList.remove("hidden-bar");

        this.updatePlayPauseButtons();
    }

    updatePlayPauseButtons() {
        const mainBtn = document.getElementById("audioMainPlayBtn");
        if (mainBtn) {
            mainBtn.innerHTML = this.isPlaying ? '<i class="fa-solid fa-pause"></i>' : '<i class="fa-solid fa-play"></i>';
        }

        // Header quick play btn if exists
        const headerBtn = document.getElementById("headerQuickPlayBtn");
        if (headerBtn) {
            headerBtn.innerHTML = this.isPlaying ? '<i class="fa-solid fa-pause"></i>' : '<i class="fa-solid fa-play"></i>';
        }
    }
}

// Global instance
window.quranPlayer = new QuranAudioPlayer();
