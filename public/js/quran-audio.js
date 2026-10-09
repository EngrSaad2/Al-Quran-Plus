/**
 * Quran Mazid - Global Audio Engine
 * Single continuous full-surah audio player with zero ayah gaps,
 * full-surah timeline seekbar, and synchronized verse tracking.
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
        this.loopMode = "continuous"; // 'continuous', 'repeat_surah', 'repeat_ayah', 'off'
        this.playbackSpeed = 1.0;
        this.speedOptions = [0.75, 1.0, 1.25, 1.5, 2.0];
        this.isSeeking = false;
        this.timestamps = null;
        this.timestampsCache = {};

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

        this.bindAudioEvents();
        this.bindSliderEvents();
        this.updateUI();

        // Notify active reciter for header icons and menus
        setTimeout(() => {
            window.dispatchEvent(new CustomEvent("quranReciterChanged", {
                detail: { reciter: this.getReciterObj() }
            }));
        }, 100);
    }

    bindAudioEvents() {
        this.audio.addEventListener("timeupdate", () => {
            this.onTimeUpdate();
        });

        this.audio.addEventListener("ended", () => {
            this.onTrackEnded();
        });

        this.audio.addEventListener("play", () => {
            this.isPlaying = true;
            this.updatePlayPauseButtons();
            this.updateMediaSession();
        });

        this.audio.addEventListener("pause", () => {
            this.isPlaying = false;
            this.updatePlayPauseButtons();
        });

        this.audio.addEventListener("loadedmetadata", () => {
            const durLabel = document.getElementById("audioDurTime");
            if (durLabel && this.audio.duration) {
                durLabel.textContent = this.formatTime(this.audio.duration);
            }
        });

        this.audio.addEventListener("error", (e) => {
            console.warn("Audio playback error:", e);
        });
    }

    bindSliderEvents() {
        const seekSlider = document.getElementById("audioSeekSlider");
        if (!seekSlider) return;

        seekSlider.addEventListener("mousedown", () => { this.isSeeking = true; });
        seekSlider.addEventListener("touchstart", () => { this.isSeeking = true; }, { passive: true });

        seekSlider.addEventListener("input", (e) => {
            this.isSeeking = true;
            const fraction = parseFloat(e.target.value) / 100;
            const curLabel = document.getElementById("audioCurTime");
            if (curLabel && this.audio.duration) {
                curLabel.textContent = this.formatTime(fraction * this.audio.duration);
            }
        });

        seekSlider.addEventListener("change", (e) => {
            const fraction = parseFloat(e.target.value) / 100;
            this.seekTo(fraction);
            this.isSeeking = false;
        });

        seekSlider.addEventListener("mouseup", () => { this.isSeeking = false; });
        seekSlider.addEventListener("touchend", () => { this.isSeeking = false; });
    }

    getReciterObj(reciterKey = this.currentReciter) {
        if (window.QURAN_DATA && window.QURAN_DATA.reciters) {
            const found = window.QURAN_DATA.reciters.find(r => 
                r.subfolder === reciterKey || r.id === reciterKey || r.name === reciterKey
            );
            if (found) return found;
        }
        return window.QURAN_DATA?.reciters?.[0] || {
            surahServer: "https://server8.mp3quran.net/afs/",
            name: "Mishary Rashid Alafasy",
            subfolder: "Alafasy_128kbps",
            quranComId: 7
        };
    }

    getSurahAudioUrl(surahId, reciterKey = this.currentReciter) {
        const sId = parseInt(surahId) || 1;
        const reciter = this.getReciterObj(reciterKey);
        if (reciter && reciter.audioSlug) {
            if (reciter.audioSlug === "saud_ash-shuraym") {
                const padSurah = String(sId).padStart(3, "0");
                return `https://download.quranicaudio.com/qdc/saud_ash-shuraym/murattal/${padSurah}.mp3`;
            }
            return `https://download.quranicaudio.com/qdc/${reciter.audioSlug}/murattal/${sId}.mp3`;
        }
        if (reciter && reciter.surahServer) {
            const padSurah = String(sId).padStart(3, "0");
            return `${reciter.surahServer}${padSurah}.mp3`;
        }
        return `https://download.quranicaudio.com/qdc/mishari_al_afasy/murattal/${sId}.mp3`;
    }

    async fetchSurahTimestamps(surahId, reciterKey = this.currentReciter) {
        const sId = parseInt(surahId) || 1;
        const reciter = this.getReciterObj(reciterKey);
        const qComId = reciter.quranComId || 7;
        const cacheKey = `quran_ts_v4_${qComId}_${sId}`;

        if (this.timestampsCache[cacheKey]) {
            return this.timestampsCache[cacheKey];
        }

        const cached = localStorage.getItem(cacheKey);
        if (cached) {
            try {
                const parsed = JSON.parse(cached);
                this.timestampsCache[cacheKey] = parsed;
                return parsed;
            } catch (e) {}
        }

        try {
            // Quran.com chapter_recitations provides standard verse boundary timestamps
            const res = await fetch(`https://api.quran.com/api/v4/chapter_recitations/${qComId}/${sId}?segments=true`);
            if (!res.ok) throw new Error("API status " + res.status);
            const data = await res.json();
            if (data && data.audio_file && data.audio_file.timestamps) {
                const rawList = data.audio_file.timestamps;
                const totalMs = rawList[rawList.length - 1].timestamp_to || 1;
                const parsed = rawList.map((t, idx) => ({
                    ayah: idx + 1,
                    fromMs: t.timestamp_from,
                    toMs: t.timestamp_to,
                    fromSec: t.timestamp_from / 1000,
                    toSec: t.timestamp_to / 1000
                }));
                this.timestampsCache[cacheKey] = parsed;
                localStorage.setItem(cacheKey, JSON.stringify(parsed));
                return parsed;
            }
        } catch (err) {
            console.warn("Could not load verse timestamps for Surah", sId, "Reciter", qComId, err);
        }
        return null;
    }

    loadSurah(surahId, autoplay = false, startAyah = 1) {
        this.currentSurahId = parseInt(surahId) || 1;
        this.currentAyahNumber = parseInt(startAyah) || 1;

        if (window.QURAN_DATA) {
            const s = window.QURAN_DATA.surahs.find(item => item.id === this.currentSurahId);
            if (s) this.totalAyahs = s.verses;
        }

        const targetUrl = this.getSurahAudioUrl(this.currentSurahId);
        const fileName = targetUrl.substring(targetUrl.lastIndexOf('/'));
        
        if (!this.audio.src || !this.audio.src.includes(fileName)) {
            this.audio.src = targetUrl;
            this.audio.playbackRate = this.playbackSpeed;
            this.audio.load();
        }

        this.updateUI();
        this.highlightActiveAyah();

        this.fetchSurahTimestamps(this.currentSurahId).then(ts => {
            this.timestamps = ts;
            if (startAyah > 1) {
                this.seekToAyah(startAyah);
            }
        });

        if (autoplay) {
            const playHandler = () => {
                if (startAyah > 1) {
                    this.seekToAyah(startAyah);
                }
                const p = this.audio.play();
                if (p !== undefined) {
                    p.then(() => {
                        this.isPlaying = true;
                        this.updatePlayPauseButtons();
                        this.updateMediaSession();
                    }).catch(err => {
                        console.log("Audio autoplay waiting for user interaction:", err);
                    });
                }
            };

            if (this.audio.readyState >= 1) {
                playHandler();
            } else {
                this.audio.addEventListener("loadedmetadata", playHandler, { once: true });
            }
        }
    }

    playSurah(surahId, startAyah = 1) {
        const sId = parseInt(surahId) || 1;
        if (this.currentSurahId !== sId) {
            this.loadSurah(sId, true, startAyah);
            return;
        }

        if (startAyah > 1) {
            this.seekToAyah(startAyah);
        }
        this.audio.play().then(() => {
            this.isPlaying = true;
            this.updatePlayPauseButtons();
            this.updateMediaSession();
        }).catch(err => console.warn(err));
    }

    playAyah(surahId, ayahNumber, totalVerses = null) {
        const sId = parseInt(surahId) || 1;
        const aNum = parseInt(ayahNumber) || 1;
        if (totalVerses) this.totalAyahs = totalVerses;

        if (this.currentSurahId !== sId) {
            this.loadSurah(sId, true, aNum);
            return;
        }

        this.seekToAyah(aNum);
        const p = this.audio.play();
        if (p !== undefined) {
            p.then(() => {
                this.isPlaying = true;
                this.updatePlayPauseButtons();
                this.updateMediaSession();
            }).catch(err => {
                console.warn("Audio play prevented:", err);
            });
        }
    }

    seekToAyah(ayahNumber) {
        this.currentAyahNumber = Math.max(1, Math.min(this.totalAyahs, parseInt(ayahNumber) || 1));
        const dur = this.audio.duration || 0;
        const reciterObj = this.getReciterObj();
        const hasExactTimestamps = reciterObj && reciterObj.quranComId;

        if (this.timestamps && this.timestamps.length >= this.currentAyahNumber) {
            const ts = this.timestamps[this.currentAyahNumber - 1];
            if (hasExactTimestamps || !dur) {
                const targetSec = ts.fromSec;
                if (!isNaN(targetSec) && isFinite(targetSec)) {
                    this.audio.currentTime = Math.max(0, targetSec);
                }
            } else {
                const hasBismillahPrefix = (this.currentSurahId !== 1 && this.currentSurahId !== 9);
                const bismillahSec = hasBismillahPrefix ? 8.0 : 0.0;
                const totalRefMs = this.timestamps[this.timestamps.length - 1]?.toMs || 1;
                const adjDur = Math.max(1, dur - bismillahSec);
                const targetSec = bismillahSec + ((ts.fromMs / totalRefMs) * adjDur);
                if (!isNaN(targetSec) && isFinite(targetSec)) {
                    this.audio.currentTime = Math.max(0, targetSec);
                }
            }
        } else if (dur > 0 && this.totalAyahs > 0) {
            const targetSec = ((this.currentAyahNumber - 1) / this.totalAyahs) * dur;
            this.audio.currentTime = Math.max(0, targetSec);
        }

        this.saveState();
        this.updateSurahTitle();
        this.highlightActiveAyah();
    }

    seekTo(fraction) {
        if (this.audio && this.audio.duration && !isNaN(this.audio.duration)) {
            const target = Math.max(0, Math.min(this.audio.duration, fraction * this.audio.duration));
            this.audio.currentTime = target;
        }
    }

    setVolume(volume) {
        const v = Math.max(0, Math.min(1, parseFloat(volume) || 1));
        this.audio.volume = v;
    }

    togglePlayPause() {
        if (!this.audio.src || this.audio.src === "") {
            this.loadSurah(this.currentSurahId, true, this.currentAyahNumber);
            return;
        }

        if (this.isPlaying) {
            this.audio.pause();
        } else {
            this.audio.play().catch(err => console.warn(err));
        }
    }

    nextAyah() {
        if (this.timestamps && this.timestamps.length > 0 && this.currentAyahNumber < this.totalAyahs) {
            this.seekToAyah(this.currentAyahNumber + 1);
        } else if (this.currentSurahId < 114) {
            this.nextSurah();
        }
    }

    prevAyah() {
        if (this.timestamps && this.timestamps.length > 0) {
            const dur = this.audio.duration || 0;
            const cur = this.audio.currentTime || 0;
            const reciterObj = this.getReciterObj();
            const isExactSec = reciterObj && reciterObj.quranComId === 7;
            const curTs = this.timestamps[this.currentAyahNumber - 1];
            const start = curTs ? ((isExactSec || !dur) ? curTs.fromSec : curTs.fromRatio * dur) : 0;

            if (cur > start + 3.0) {
                this.seekToAyah(this.currentAyahNumber);
            } else if (this.currentAyahNumber > 1) {
                this.seekToAyah(this.currentAyahNumber - 1);
            } else if (this.currentSurahId > 1) {
                this.prevSurah();
            }
        } else {
            if (this.audio.currentTime > 5) {
                this.audio.currentTime = 0;
                this.audio.play();
            } else if (this.currentSurahId > 1) {
                this.prevSurah();
            }
        }
    }

    nextTrack() {
        this.nextSurah();
    }

    prevTrack() {
        if (this.audio.currentTime > 5) {
            this.audio.currentTime = 0;
            this.audio.play();
        } else {
            this.prevSurah();
        }
    }

    nextSurah() {
        if (this.currentSurahId < 114) {
            const nextId = this.currentSurahId + 1;
            if (window.location.pathname.includes('/surah/')) {
                const baseUrl = window.APP_BASE_URL || '';
                window.location.href = `${baseUrl}/surah/${nextId}?autoplay=1`;
            } else {
                this.playSurah(nextId, 1);
            }
        }
    }

    prevSurah() {
        if (this.currentSurahId > 1) {
            const prevId = this.currentSurahId - 1;
            if (window.location.pathname.includes('/surah/')) {
                const baseUrl = window.APP_BASE_URL || '';
                window.location.href = `${baseUrl}/surah/${prevId}?autoplay=1`;
            } else {
                this.playSurah(prevId, 1);
            }
        }
    }

    onTrackEnded() {
        if (this.loopMode === "repeat_ayah") {
            this.seekToAyah(this.currentAyahNumber);
            this.audio.play();
        } else if (this.loopMode === "repeat_surah") {
            this.audio.currentTime = 0;
            this.audio.play();
        } else if (this.loopMode === "continuous") {
            this.nextSurah();
        } else {
            this.isPlaying = false;
            this.updatePlayPauseButtons();
        }
    }

    onTimeUpdate() {
        const cur = this.audio.currentTime || 0;
        const dur = this.audio.duration || 0;

        const curLabel = document.getElementById("audioCurTime");
        const durLabel = document.getElementById("audioDurTime");
        const seekSlider = document.getElementById("audioSeekSlider");

        if (curLabel) curLabel.textContent = this.formatTime(cur);
        if (durLabel && dur > 0) durLabel.textContent = this.formatTime(dur);

        if (seekSlider && dur > 0 && !this.isSeeking) {
            seekSlider.value = (cur / dur) * 100;
        }

        this.syncActiveAyahFromTime(cur, dur);
    }

    syncActiveAyahFromTime(cur, dur) {
        let matchedAyah = null;
        const reciterObj = this.getReciterObj();
        const hasExactTimestamps = reciterObj && reciterObj.quranComId;

        if (this.timestamps && this.timestamps.length > 0) {
            if (hasExactTimestamps) {
                // Exact Quran.com chapter recitation timestamps
                for (const t of this.timestamps) {
                    if (cur >= t.fromSec && cur < t.toSec) {
                        matchedAyah = t.ayah;
                        break;
                    }
                }
                if (!matchedAyah && cur >= (this.timestamps[this.timestamps.length - 1]?.fromSec || 0)) {
                    matchedAyah = this.totalAyahs;
                }
            } else {
                // Non-exact reciter (e.g. Maher from mp3quran)
                // In mp3quran full surahs (except Surah 1 and 9), reciter starts with Bismillah (~8.0s)
                const hasBismillahPrefix = (this.currentSurahId !== 1 && this.currentSurahId !== 9);
                const bismillahSec = hasBismillahPrefix ? 8.0 : 0.0;

                if (cur < bismillahSec) {
                    matchedAyah = 1;
                } else {
                    const adjCur = cur - bismillahSec;
                    const adjDur = Math.max(1, dur - bismillahSec);
                    const totalRefMs = this.timestamps[this.timestamps.length - 1]?.toMs || 1;

                    for (const t of this.timestamps) {
                        const startRatio = t.fromMs / totalRefMs;
                        const endRatio = t.toMs / totalRefMs;
                        const scaledStart = startRatio * adjDur;
                        const scaledEnd = endRatio * adjDur;

                        if (adjCur >= scaledStart && adjCur < scaledEnd) {
                            matchedAyah = t.ayah;
                            break;
                        }
                    }
                }
            }
        } else if (dur > 0 && this.totalAyahs > 0) {
            const ratio = cur / dur;
            matchedAyah = Math.min(this.totalAyahs, Math.max(1, Math.floor(ratio * this.totalAyahs) + 1));
        }

        if (matchedAyah && matchedAyah !== this.currentAyahNumber) {
            this.currentAyahNumber = matchedAyah;
            this.saveState();
            this.updateSurahTitle();
            this.highlightActiveAyah();
        }
    }

    setReciter(reciterSubfolder, reciterName) {
        this.currentReciter = reciterSubfolder;
        this.currentReciterName = reciterName;
        localStorage.setItem("quran_reciter", reciterSubfolder);
        localStorage.setItem("quran_reciter_name", reciterName);

        const wasPlaying = this.isPlaying;
        const currentAyah = this.currentAyahNumber || 1;

        const newUrl = this.getSurahAudioUrl(this.currentSurahId, reciterSubfolder);
        this.audio.src = newUrl;
        this.audio.playbackRate = this.playbackSpeed;
        this.audio.load();

        this.updateUI();

        this.fetchSurahTimestamps(this.currentSurahId, reciterSubfolder).then(ts => {
            this.timestamps = ts;
            this.seekToAyah(currentAyah);
            if (wasPlaying) {
                this.audio.play().then(() => {
                    this.isPlaying = true;
                    this.updatePlayPauseButtons();
                    this.updateMediaSession();
                }).catch(e => console.warn(e));
            }
        });

        // Dispatch global event for header avatar & menus
        window.dispatchEvent(new CustomEvent("quranReciterChanged", {
            detail: { reciter: this.getReciterObj(reciterSubfolder) }
        }));
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
        const inlineSpeed = document.getElementById("audioInlineSpeed");
        if (inlineSpeed) {
            inlineSpeed.textContent = `${this.playbackSpeed}x`;
        }
    }

    formatTime(seconds) {
        if (!seconds || isNaN(seconds) || seconds < 0) return "0:00";
        const total = Math.floor(seconds);
        const h = Math.floor(total / 3600);
        const m = Math.floor((total % 3600) / 60);
        const s = Math.floor(total % 60);
        if (h > 0) {
            return `${h}:${m < 10 ? '0' : ''}${m}:${s < 10 ? '0' : ''}${s}`;
        }
        return `${m}:${s < 10 ? '0' : ''}${s}`;
    }

    saveState() {
        localStorage.setItem("quran_last_surah", this.currentSurahId);
        localStorage.setItem("quran_last_ayah", this.currentAyahNumber);
    }

    highlightActiveAyah() {
        const prevActive = document.querySelector(".ayah-card.active-playing");
        const target = document.getElementById(`ayah-${this.currentAyahNumber}`);
        
        if (prevActive && prevActive !== target) {
            prevActive.classList.remove("active-playing");
        }

        if (target && !target.classList.contains("active-playing")) {
            target.classList.add("active-playing");
            const rect = target.getBoundingClientRect();
            if (rect.top < 80 || rect.bottom > window.innerHeight - 80) {
                target.scrollIntoView({ behavior: "smooth", block: "center" });
            }
        }
    }

    updateSurahTitle() {
        const nameEl = document.getElementById("audioSurahName");
        if (!nameEl) return;

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
        const ayahNum = isBn && window.toBanglaNumber ? window.toBanglaNumber(this.currentAyahNumber) : this.currentAyahNumber;

        nameEl.textContent = isBn 
            ? `${banglaName} • আয়াত ${ayahNum}` 
            : `${surahName} • Verse ${this.currentAyahNumber}`;

        if (isBn) nameEl.classList.add("font-bangla");
        else nameEl.classList.remove("font-bangla");
    }

    updateUI() {
        this.updateSurahTitle();

        const reciterEl = document.getElementById("audioReciterName");
        const bar = document.getElementById("globalAudioBar");
        const loopBtn = document.getElementById("audioLoopBtn");
        const speedBtn = document.getElementById("audioSpeedBtn");
        const lang = window.currentQuranLang || localStorage.getItem("quran_lang") || "bn";
        const isBn = lang === "bn";

        if (loopBtn) loopBtn.title = isBn ? "রিপিট / লুপ" : "Repeat / Loop";
        if (speedBtn) speedBtn.title = isBn ? "প্লেব্যাক গতি" : "Playback Speed";
        const inlineSpeed = document.getElementById("audioInlineSpeed");
        if (inlineSpeed) {
            inlineSpeed.textContent = `${this.playbackSpeed}x`;
            inlineSpeed.closest("button")?.setAttribute("title", isBn ? "প্লেব্যাক গতি" : "Playback Speed");
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

    updateMediaSession() {
        if (!("mediaSession" in navigator)) return;

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

        try {
            navigator.mediaSession.metadata = new MediaMetadata({
                title: isBn ? `${banglaName} (আয়াত ${this.currentAyahNumber})` : `${surahName} (Ayah ${this.currentAyahNumber})`,
                artist: this.currentReciterName,
                album: isBn ? "কুরআন মাজিদ" : "Quran Mazid"
            });

            navigator.mediaSession.setActionHandler("play", () => this.togglePlayPause());
            navigator.mediaSession.setActionHandler("pause", () => this.togglePlayPause());
            navigator.mediaSession.setActionHandler("previoustrack", () => this.prevAyah());
            navigator.mediaSession.setActionHandler("nexttrack", () => this.nextAyah());
        } catch (e) {}
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
