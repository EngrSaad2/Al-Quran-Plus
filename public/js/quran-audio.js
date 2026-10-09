/**
 * Quran Mazid - Global Audio Engine
 * Supports seamless studio Surah recitation and synchronized Ayah playback
 */

const FULL_SURAH_SERVERS = {
    "Alafasy_128kbps": "https://server8.mp3quran.net/afs/",
    "ar.alafasy": "https://server8.mp3quran.net/afs/",
    "Abdurrahmaan_As-Sudais_192kbps": "https://server11.mp3quran.net/sds/",
    "ar.abdurrahmaansudais": "https://server11.mp3quran.net/sds/",
    "Maher_AlMuaiqly_64kbps": "https://server12.mp3quran.net/maher/",
    "ar.mahermuaiqly": "https://server12.mp3quran.net/maher/",
    "Saood_ash-Shuraym_128kbps": "https://server7.mp3quran.net/shur/",
    "ar.saoodshuraym": "https://server7.mp3quran.net/shur/",
    "Abdullah_Basfar_192kbps": "https://server6.mp3quran.net/bsfr/",
    "ar.abdullahbasfar": "https://server6.mp3quran.net/bsfr/",
    "Minshawy_Murattal_128kbps": "https://server10.mp3quran.net/minsh/",
    "ar.minshawi": "https://server10.mp3quran.net/minsh/",
    "Hudhaify_128kbps": "https://server9.mp3quran.net/hthfi/",
    "ar.hudhaify": "https://server9.mp3quran.net/hthfi/",
    "Ahmed_ibn_Ali_al-Ajamy_128kbps_ketaballah.net": "https://server10.mp3quran.net/ajm/128/",
    "ar.ahmedajamy": "https://server10.mp3quran.net/ajm/128/"
};

class QuranAudioPlayer {
    constructor() {
        this.audio = new Audio();
        this.preloader = new Audio();
        this.isPlaying = false;
        this.playMode = "surah"; // 'surah' (full continuous recitation) or 'ayah' (verse by verse)
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
            console.warn("Audio playback error, trying fallback:", e);
            if (this.playMode === "ayah") {
                this.nextAyah();
            }
        });
    }

    getFullSurahAudioUrl(surahId, reciter = this.currentReciter) {
        const prefix = FULL_SURAH_SERVERS[reciter] || "https://server8.mp3quran.net/afs/";
        const s = String(surahId).padStart(3, "0");
        return `${prefix}${s}.mp3`;
    }

    getAyahAudioUrl(surah, ayah, reciter = this.currentReciter) {
        const s = String(surah).padStart(3, "0");
        const a = String(ayah).padStart(3, "0");
        return `https://everyayah.com/data/${reciter}/${s}${a}.mp3`;
    }

    // Set surah in player without forcing playback unless autoplay = true
    loadSurah(surahId, autoplay = false) {
        this.currentSurahId = parseInt(surahId) || 1;
        this.currentAyahNumber = 1;
        this.playMode = "surah";

        if (window.QURAN_DATA) {
            const s = window.QURAN_DATA.surahs.find(item => item.id === this.currentSurahId);
            if (s) this.totalAyahs = s.verses;
        }

        const url = this.getFullSurahAudioUrl(this.currentSurahId);
        if (this.audio.src !== url) {
            this.audio.src = url;
            this.audio.playbackRate = this.playbackSpeed;
        }

        this.updateUI();

        if (autoplay) {
            this.audio.play().then(() => {
                this.isPlaying = true;
                this.saveState();
                this.updateUI();
            }).catch(err => {
                console.log("Autoplay prevented:", err);
            });
            this.preloadNextSurah();
        }
    }

    // Play full Surah audio continuously with natural Qari rhythm
    playSurah(surahId) {
        this.currentSurahId = parseInt(surahId) || 1;
        this.currentAyahNumber = 1;
        this.playMode = "surah";

        if (window.QURAN_DATA) {
            const s = window.QURAN_DATA.surahs.find(item => item.id === this.currentSurahId);
            if (s) this.totalAyahs = s.verses;
        }

        const url = this.getFullSurahAudioUrl(this.currentSurahId);
        this.audio.src = url;
        this.audio.playbackRate = this.playbackSpeed;

        this.audio.play().then(() => {
            this.isPlaying = true;
            this.saveState();
            this.updateUI();
        }).catch(err => {
            console.log("Audio play prevented or loading:", err);
        });

        this.preloadNextSurah();
    }

    // Play single Ayah audio
    playAyah(surahId, ayahNumber, totalVerses = null) {
        this.currentSurahId = parseInt(surahId);
        this.currentAyahNumber = parseInt(ayahNumber);
        this.playMode = "ayah";

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

        // Preload next ayah immediately to prevent delay
        this.preloadNextAyah();
    }

    preloadNextAyah() {
        if (this.currentAyahNumber < this.totalAyahs) {
            const nextUrl = this.getAyahAudioUrl(this.currentSurahId, this.currentAyahNumber + 1);
            this.preloader.preload = "auto";
            this.preloader.src = nextUrl;
        }
    }

    preloadNextSurah() {
        if (this.currentSurahId < 114) {
            const nextUrl = this.getFullSurahAudioUrl(this.currentSurahId + 1);
            this.preloader.preload = "auto";
            this.preloader.src = nextUrl;
        }
    }

    togglePlayPause() {
        if (!this.audio.src || this.audio.src === "" || this.audio.src.endsWith('/null') || this.audio.src.endsWith('/undefined')) {
            if (this.playMode === "ayah") {
                this.playAyah(this.currentSurahId, this.currentAyahNumber);
            } else {
                this.playSurah(this.currentSurahId);
            }
            return;
        }

        if (this.isPlaying) {
            this.audio.pause();
        } else {
            this.audio.play().catch(err => console.warn(err));
        }
    }

    nextTrack() {
        if (this.playMode === "ayah") {
            this.nextAyah();
        } else {
            this.nextSurah();
        }
    }

    prevTrack() {
        if (this.playMode === "ayah") {
            this.prevAyah();
        } else {
            this.prevSurah();
        }
    }

    nextSurah() {
        if (this.currentSurahId < 114) {
            this.currentSurahId++;
            if (window.location.pathname.includes('/surah/')) {
                const baseUrl = window.APP_BASE_URL || '';
                window.location.href = `${baseUrl}/surah/${this.currentSurahId}?autoplay=1`;
            } else {
                this.playSurah(this.currentSurahId);
            }
        }
    }

    prevSurah() {
        if (this.currentSurahId > 1) {
            this.currentSurahId--;
            if (window.location.pathname.includes('/surah/')) {
                const baseUrl = window.APP_BASE_URL || '';
                window.location.href = `${baseUrl}/surah/${this.currentSurahId}?autoplay=1`;
            } else {
                this.playSurah(this.currentSurahId);
            }
        }
    }

    nextAyah() {
        if (this.playMode === "surah") {
            this.nextSurah();
            return;
        }
        if (this.currentAyahNumber < this.totalAyahs) {
            this.playAyah(this.currentSurahId, this.currentAyahNumber + 1);
        } else if (this.currentSurahId < 114) {
            this.currentSurahId++;
            this.playAyah(this.currentSurahId, 1);
            if (window.location.pathname.includes('/surah/')) {
                const baseUrl = window.APP_BASE_URL || '';
                window.location.href = `${baseUrl}/surah/${this.currentSurahId}?autoplay=1`;
            }
        }
    }

    prevAyah() {
        if (this.playMode === "surah") {
            this.prevSurah();
            return;
        }
        if (this.currentAyahNumber > 1) {
            this.playAyah(this.currentSurahId, this.currentAyahNumber - 1);
        } else if (this.currentSurahId > 1) {
            this.currentSurahId--;
            this.playAyah(this.currentSurahId, 1);
            if (window.location.pathname.includes('/surah/')) {
                const baseUrl = window.APP_BASE_URL || '';
                window.location.href = `${baseUrl}/surah/${this.currentSurahId}?autoplay=1`;
            }
        }
    }

    onTrackEnded() {
        if (this.playMode === "surah") {
            if (this.loopMode === "repeat_surah" || this.loopMode === "repeat_ayah") {
                this.audio.currentTime = 0;
                this.audio.play();
            } else if (this.loopMode === "continuous") {
                this.nextSurah();
            } else {
                this.isPlaying = false;
                this.updatePlayPauseButtons();
            }
        } else {
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
            } else {
                this.isPlaying = false;
                this.updatePlayPauseButtons();
            }
        }
    }

    toggleLoopMode() {
        const modes = ["continuous", "repeat_surah", "repeat_ayah", "off"];
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
            if (this.playMode === "ayah") {
                this.playAyah(this.currentSurahId, this.currentAyahNumber);
            } else {
                this.playSurah(this.currentSurahId);
            }
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
        if (this.playMode !== "ayah") return;
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
            if (this.playMode === "ayah") {
                const ayahNum = isBn && window.toBanglaNumber ? window.toBanglaNumber(this.currentAyahNumber) : this.currentAyahNumber;
                nameEl.textContent = isBn ? `${banglaName} (আয়াত ${ayahNum})` : `Surah ${surahName} (Ayah ${this.currentAyahNumber})`;
            } else {
                const surahNum = isBn && window.toBanglaNumber ? window.toBanglaNumber(this.currentSurahId) : this.currentSurahId;
                nameEl.textContent = isBn ? `${banglaName} (${surahNum})` : `Surah ${surahName} (${this.currentSurahId})`;
            }
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
