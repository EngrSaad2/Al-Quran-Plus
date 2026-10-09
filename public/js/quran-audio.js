/**
 * Quran Mazid - Global Audio Engine
 * Dual-buffer gapless playback with synchronized real-time Ayah tracking
 */

class QuranAudioPlayer {
    constructor() {
        this.audioA = new Audio();
        this.audioB = new Audio();
        this.activeAudio = this.audioA;
        this.inactiveAudio = this.audioB;
        this.isPlaying = false;
        this.currentSurahId = 1;
        this.currentAyahNumber = 1;
        this.totalAyahs = 7;
        this.currentReciter = "Alafasy_128kbps";
        this.currentReciterName = "Mishary Rashid Alafasy";
        this.loopMode = "continuous"; // 'off', 'repeat_ayah', 'repeat_surah', 'continuous'
        this.playbackSpeed = 1.0;
        this.speedOptions = [0.75, 1.0, 1.25, 1.5, 2.0];
        this.isTransitioning = false;

        this.init();
    }

    init() {
        const savedSurah = localStorage.getItem("quran_last_surah");
        const savedAyah = localStorage.getItem("quran_last_ayah");
        const savedReciter = localStorage.getItem("quran_reciter");
        const savedReciterName = localStorage.getItem("quran_reciter_name");

        if (savedSurah) this.currentSurahId = parseInt(savedSurah);
        if (savedAyah) this.currentAyahNumber = parseInt(savedAyah);
        if (savedReciter) this.currentReciter = savedReciter;
        if (savedReciterName) this.currentReciterName = savedReciterName;

        this.bindAudioEvents(this.audioA);
        this.bindAudioEvents(this.audioB);

        this.updateUI();
    }

    bindAudioEvents(audio) {
        audio.addEventListener("timeupdate", () => {
            if (audio === this.activeAudio) {
                this.onTimeUpdate();
            }
        });
        audio.addEventListener("ended", () => {
            if (audio === this.activeAudio) {
                this.onTrackEnded();
            }
        });
        audio.addEventListener("play", () => {
            if (audio === this.activeAudio) {
                this.isPlaying = true;
                this.updatePlayPauseButtons();
            }
        });
        audio.addEventListener("pause", () => {
            if (audio === this.activeAudio && !this.isTransitioning) {
                this.isPlaying = false;
                this.updatePlayPauseButtons();
            }
        });
        audio.addEventListener("error", (e) => {
            if (audio === this.activeAudio) {
                console.warn("Audio playback error:", e);
                if (this.currentAyahNumber < this.totalAyahs) {
                    this.nextAyah();
                }
            }
        });
    }

    getAyahAudioUrl(surah, ayah, reciter = this.currentReciter) {
        const s = String(surah).padStart(3, "0");
        const a = String(ayah).padStart(3, "0");
        return `https://everyayah.com/data/${reciter}/${s}${a}.mp3`;
    }

    // Set or sync surah in player without forcing playback unless autoplay = true
    loadSurah(surahId, autoplay = false) {
        this.currentSurahId = parseInt(surahId) || 1;
        this.currentAyahNumber = 1;

        if (window.QURAN_DATA) {
            const s = window.QURAN_DATA.surahs.find(item => item.id === this.currentSurahId);
            if (s) this.totalAyahs = s.verses;
        }

        const url = this.getAyahAudioUrl(this.currentSurahId, 1);
        this.activeAudio.src = url;
        this.activeAudio.playbackRate = this.playbackSpeed;
        this.activeAudio.load();

        this.updateUI();
        this.highlightActiveAyah();

        if (autoplay) {
            this.playAyah(this.currentSurahId, 1);
        } else {
            this.preloadNextAyah();
        }
    }

    playSurah(surahId, startAyah = 1) {
        this.playAyah(surahId, startAyah);
    }

    playAyah(surahId, ayahNumber, totalVerses = null) {
        this.currentSurahId = parseInt(surahId);
        this.currentAyahNumber = parseInt(ayahNumber);
        this.isTransitioning = false;

        if (totalVerses) {
            this.totalAyahs = totalVerses;
        } else if (window.QURAN_DATA) {
            const s = window.QURAN_DATA.surahs.find(item => item.id === this.currentSurahId);
            if (s) this.totalAyahs = s.verses;
        }

        try { this.inactiveAudio.pause(); } catch(e) {}

        const url = this.getAyahAudioUrl(this.currentSurahId, this.currentAyahNumber);
        this.activeAudio.src = url;
        this.activeAudio.playbackRate = this.playbackSpeed;

        const playPromise = this.activeAudio.play();
        if (playPromise !== undefined) {
            playPromise.then(() => {
                this.isPlaying = true;
                this.saveState();
                this.updateUI();
                this.highlightActiveAyah();
                this.preloadNextAyah();
            }).catch(err => {
                console.log("Audio play prevented or loading:", err);
            });
        }
    }

    preloadNextAyah() {
        if (this.currentAyahNumber < this.totalAyahs) {
            const nextUrl = this.getAyahAudioUrl(this.currentSurahId, this.currentAyahNumber + 1);
            this.inactiveAudio.preload = "auto";
            this.inactiveAudio.src = nextUrl;
            this.inactiveAudio.load();
        }
    }

    // Seamless gapless switch to the next ayah using preloaded audio buffer
    transitionToNextAyah() {
        if (this.isTransitioning) return;
        this.isTransitioning = true;

        if (this.currentAyahNumber < this.totalAyahs) {
            this.currentAyahNumber++;

            // Swap active and inactive audio objects
            const prevAudio = this.activeAudio;
            this.activeAudio = this.inactiveAudio;
            this.inactiveAudio = prevAudio;

            this.activeAudio.playbackRate = this.playbackSpeed;
            this.activeAudio.volume = prevAudio.volume;

            const playPromise = this.activeAudio.play();
            if (playPromise !== undefined) {
                playPromise.then(() => {
                    this.isPlaying = true;
                    this.saveState();
                    this.updateUI();
                    this.highlightActiveAyah();
                    this.isTransitioning = false;
                    this.preloadNextAyah();
                }).catch(err => {
                    console.warn("Transition play error:", err);
                    this.isTransitioning = false;
                    this.playAyah(this.currentSurahId, this.currentAyahNumber);
                });
            } else {
                this.isTransitioning = false;
            }
        } else {
            // Surah finished
            this.isTransitioning = false;
            if (this.loopMode === "repeat_surah") {
                this.playAyah(this.currentSurahId, 1);
            } else if (this.loopMode === "continuous" && this.currentSurahId < 114) {
                this.currentSurahId++;
                if (window.location.pathname.includes('/surah/')) {
                    const baseUrl = window.APP_BASE_URL || '';
                    window.location.href = `${baseUrl}/surah/${this.currentSurahId}?autoplay=1`;
                } else {
                    this.playAyah(this.currentSurahId, 1);
                }
            } else {
                this.isPlaying = false;
                this.updatePlayPauseButtons();
            }
        }
    }

    onTrackEnded() {
        if (this.loopMode === "repeat_ayah") {
            this.activeAudio.currentTime = 0;
            this.activeAudio.play();
        } else {
            this.transitionToNextAyah();
        }
    }

    togglePlayPause() {
        if (!this.activeAudio.src || this.activeAudio.src === "" || this.activeAudio.src.endsWith('/null') || this.activeAudio.src.endsWith('/undefined')) {
            this.playAyah(this.currentSurahId, this.currentAyahNumber);
            return;
        }

        if (this.isPlaying) {
            this.activeAudio.pause();
        } else {
            this.activeAudio.play().catch(err => console.warn(err));
        }
    }

    nextAyah() {
        if (this.currentAyahNumber < this.totalAyahs) {
            this.playAyah(this.currentSurahId, this.currentAyahNumber + 1);
        } else if (this.currentSurahId < 114) {
            this.currentSurahId++;
            if (window.location.pathname.includes('/surah/')) {
                const baseUrl = window.APP_BASE_URL || '';
                window.location.href = `${baseUrl}/surah/${this.currentSurahId}?autoplay=1`;
            } else {
                this.playAyah(this.currentSurahId, 1);
            }
        }
    }

    prevAyah() {
        if (this.currentAyahNumber > 1) {
            this.playAyah(this.currentSurahId, this.currentAyahNumber - 1);
        } else if (this.currentSurahId > 1) {
            this.currentSurahId--;
            if (window.location.pathname.includes('/surah/')) {
                const baseUrl = window.APP_BASE_URL || '';
                window.location.href = `${baseUrl}/surah/${this.currentSurahId}?autoplay=1`;
            } else {
                this.playAyah(this.currentSurahId, 1);
            }
        }
    }

    nextTrack() {
        this.nextAyah();
    }

    prevTrack() {
        this.prevAyah();
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
        this.activeAudio.playbackRate = this.playbackSpeed;
        this.inactiveAudio.playbackRate = this.playbackSpeed;

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
            this.playAyah(this.currentSurahId, this.currentAyahNumber);
        } else {
            this.updateUI();
        }
    }

    seekTo(fraction) {
        if (this.activeAudio.duration) {
            this.activeAudio.currentTime = fraction * this.activeAudio.duration;
        }
    }

    setVolume(volume) {
        const v = Math.max(0, Math.min(1, volume));
        this.activeAudio.volume = v;
        this.inactiveAudio.volume = v;
    }

    onTimeUpdate() {
        const cur = this.activeAudio.currentTime || 0;
        const dur = this.activeAudio.duration || 0;

        const curLabel = document.getElementById("audioCurTime");
        const durLabel = document.getElementById("audioDurTime");
        const seekSlider = document.getElementById("audioSeekSlider");

        if (curLabel) curLabel.textContent = this.formatTime(cur);
        if (durLabel && dur) durLabel.textContent = this.formatTime(dur);
        if (seekSlider && dur) {
            seekSlider.value = (cur / dur) * 100;
        }

        // Seamless gapless handoff near the end of the audio file (0.05s)
        if (dur > 0 && (dur - cur) <= 0.05 && !this.isTransitioning && this.isPlaying) {
            if (this.loopMode === "repeat_ayah") {
                this.activeAudio.currentTime = 0;
            } else {
                this.transitionToNextAyah();
            }
        }
    }

    formatTime(seconds) {
        if (!seconds || isNaN(seconds)) return "0:00";
        const m = Math.floor(seconds / 60);
        const s = Math.floor(seconds % 60);
        return `${m}:${s < 10 ? '0' : ''}${s}`;
    }

    saveState() {
        localStorage.setItem("quran_last_surah", this.currentSurahId);
        localStorage.setItem("quran_last_ayah", this.currentAyahNumber);
    }

    highlightActiveAyah() {
        document.querySelectorAll(".ayah-card.active-playing").forEach(el => {
            el.classList.remove("active-playing");
        });

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

        const lang = window.currentQuranLang || localStorage.getItem("quran_lang") || "bn";
        const isBn = lang === "bn";
        const nameEl = document.getElementById("audioSurahName");
        const reciterEl = document.getElementById("audioReciterName");
        const bar = document.getElementById("globalAudioBar");
        const loopBtn = document.getElementById("audioLoopBtn");
        const speedBtn = document.getElementById("audioSpeedBtn");

        if (loopBtn) loopBtn.title = isBn ? "রিপিট / লুপ" : "Repeat / Loop";
        if (speedBtn) speedBtn.title = isBn ? "প্লেব্যাক গতি" : "Playback Speed";

        if (nameEl) {
            const ayahNum = isBn && window.toBanglaNumber ? window.toBanglaNumber(this.currentAyahNumber) : this.currentAyahNumber;
            nameEl.textContent = isBn ? `${banglaName} (আয়াত ${ayahNum})` : `Surah ${surahName} (Ayah ${this.currentAyahNumber})`;
            if (isBn) nameEl.classList.add("font-bangla");
            else nameEl.classList.remove("font-bangla");
        }
        if (reciterEl) reciterEl.textContent = this.currentReciterName;
        if (bar) bar.classList.remove("hidden-bar");

        this.updatePlayPauseButtons();
    }

    updatePlayPauseButtons() {
        const mainBtn = document.getElementById("audioMainPlayBtn");
        if (mainBtn) {
            mainBtn.innerHTML = this.isPlaying ? '<i class="fa-solid fa-pause"></i>' : '<i class="fa-solid fa-play"></i>';
        }

        const headerBtn = document.getElementById("headerQuickPlayBtn");
        if (headerBtn) {
            headerBtn.innerHTML = this.isPlaying ? '<i class="fa-solid fa-pause"></i>' : '<i class="fa-solid fa-play"></i>';
        }
    }
}

// Global instance
window.quranPlayer = new QuranAudioPlayer();

// Listen to language changes
window.addEventListener("quranLanguageChanged", () => {
    if (window.quranPlayer) {
        window.quranPlayer.updateUI();
    }
});
