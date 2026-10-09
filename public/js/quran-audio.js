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
        const cacheKey = `quran_ts_v5_${qComId}_${sId}`;

        if (this.timestampsCache[cacheKey]) {
            return this.timestampsCache[cacheKey];
        }

        const cached = localStorage.getItem(cacheKey);
        if (cached) {
            try {
                const parsed = JSON.parse(cached);
                if (Array.isArray(parsed) && parsed.length > 0) {
                    this.timestampsCache[cacheKey] = parsed;
                    return parsed;
                }
            } catch (e) {}
        }

        try {
            // Quran.com chapter_recitations provides standard verse boundary timestamps
            const res = await fetch(`https://api.quran.com/api/v4/chapter_recitations/${qComId}/${sId}?segments=true`);
            if (!res.ok) throw new Error("API status " + res.status);
            const data = await res.json();
            if (data && data.audio_file && data.audio_file.timestamps) {
                const rawList = data.audio_file.timestamps;
                const parsed = rawList.map((t, idx) => {
                    const ayahNum = t.verse_key ? parseInt(t.verse_key.split(':')[1]) : (idx + 1);
                    return {
                        ayah: ayahNum,
                        fromMs: t.timestamp_from,
                        toMs: t.timestamp_to,
                        fromSec: t.timestamp_from / 1000,
                        toSec: t.timestamp_to / 1000
                    };
                }).sort((a, b) => a.ayah - b.ayah);

                this.timestampsCache[cacheKey] = parsed;
                try {
                    localStorage.setItem(cacheKey, JSON.stringify(parsed));
                } catch (e) {}
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
        this.pendingSeekAyah = parseInt(startAyah) || 1;

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

        const tsPromise = this.fetchSurahTimestamps(this.currentSurahId);
        const metaPromise = new Promise(resolve => {
            if (this.audio.readyState >= 1) {
                resolve();
            } else {
                this.audio.addEventListener("loadedmetadata", resolve, { once: true });
            }
        });

        Promise.all([tsPromise, metaPromise]).then(([ts]) => {
            this.timestamps = ts;
            if (this.pendingSeekAyah) {
                this.seekToAyah(this.pendingSeekAyah);
                this.pendingSeekAyah = null;
            }
            if (autoplay) {
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
            }
        }).catch(err => {
            console.warn("Error initializing surah playback:", err);
        });
    }

    playSurah(surahId, startAyah = 1) {
        const sId = parseInt(surahId) || 1;
        const aNum = parseInt(startAyah) || 1;
        if (this.currentSurahId !== sId) {
            this.loadSurah(sId, true, aNum);
            return;
        }

        if (!this.timestamps) {
            this.pendingSeekAyah = aNum;
            this.fetchSurahTimestamps(sId).then(ts => {
                this.timestamps = ts;
                this.seekToAyah(aNum);
                this.audio.play().then(() => {
                    this.isPlaying = true;
                    this.updatePlayPauseButtons();
                    this.updateMediaSession();
                }).catch(err => console.warn(err));
            });
            return;
        }

        this.seekToAyah(aNum);
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

        if (!this.timestamps) {
            this.pendingSeekAyah = aNum;
            this.fetchSurahTimestamps(sId).then(ts => {
                this.timestamps = ts;
                this.seekToAyah(aNum);
                this.audio.play().then(() => {
                    this.isPlaying = true;
                    this.updatePlayPauseButtons();
                    this.updateMediaSession();
                }).catch(err => console.warn(err));
            });
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
        const aNum = Math.max(1, Math.min(this.totalAyahs, parseInt(ayahNumber) || 1));
        this.currentAyahNumber = aNum;
        const dur = this.audio.duration || 0;
        const reciterObj = this.getReciterObj();
        const hasExactTimestamps = reciterObj && reciterObj.quranComId;

        if (this.timestamps && this.timestamps.length > 0) {
            const ts = this.timestamps.find(t => t.ayah === aNum) || this.timestamps[aNum - 1];
            if (ts) {
                let targetSec = ts.fromSec;
                if (!hasExactTimestamps && dur > 0) {
                    const hasBismillahPrefix = (this.currentSurahId !== 1 && this.currentSurahId !== 9);
                    const bismillahSec = hasBismillahPrefix ? 8.0 : 0.0;
                    const totalRefMs = this.timestamps[this.timestamps.length - 1]?.toMs || 1;
                    const adjDur = Math.max(1, dur - bismillahSec);
                    targetSec = bismillahSec + ((ts.fromMs / totalRefMs) * adjDur);
                }
                if (!isNaN(targetSec) && isFinite(targetSec)) {
                    this.audio.currentTime = Math.max(0, targetSec);
                }
            }
        } else if (dur > 0 && this.totalAyahs > 0) {
            // Only as a last-resort fallback if timestamps cannot be fetched
            const targetSec = ((aNum - 1) / this.totalAyahs) * dur;
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
        if (!this.timestamps || this.timestamps.length === 0) {
            if (dur > 0 && this.totalAyahs > 0) {
                const ratio = cur / dur;
                const matchedAyah = Math.min(this.totalAyahs, Math.max(1, Math.floor(ratio * this.totalAyahs) + 1));
                if (matchedAyah !== this.currentAyahNumber) {
                    this.currentAyahNumber = matchedAyah;
                    this.saveState();
                    this.updateSurahTitle();
                    this.highlightActiveAyah();
                }
            }
            return;
        }

        let matchedAyah = null;
        const reciterObj = this.getReciterObj();
        const hasExactTimestamps = reciterObj && reciterObj.quranComId;

        if (hasExactTimestamps) {
            // Exact Quran.com chapter recitation timestamps
            const len = this.timestamps.length;
            for (let i = 0; i < len; i++) {
                const t = this.timestamps[i];
                const nextT = this.timestamps[i + 1];

                // Current verse remains active until the next verse's recitation actually begins
                // (+ 0.05s buffer to prevent early flip before reciter speaks the next ayah).
                if (nextT) {
                    if (cur >= t.fromSec && cur < (nextT.fromSec + 0.05)) {
                        matchedAyah = t.ayah;
                        break;
                    }
                } else {
                    // Last ayah in surah
                    if (cur >= t.fromSec) {
                        matchedAyah = t.ayah;
                        break;
                    }
                }
            }

            if (!matchedAyah && cur < (this.timestamps[0]?.fromSec || 0)) {
                matchedAyah = 1;
            }
        } else {
            // Non-exact reciter fallback
            const hasBismillahPrefix = (this.currentSurahId !== 1 && this.currentSurahId !== 9);
            const bismillahSec = hasBismillahPrefix ? 8.0 : 0.0;

            if (cur < bismillahSec) {
                matchedAyah = 1;
            } else {
                const adjCur = cur - bismillahSec;
                const adjDur = Math.max(1, dur - bismillahSec);
                const totalRefMs = this.timestamps[this.timestamps.length - 1]?.toMs || 1;
                const len = this.timestamps.length;

                for (let i = 0; i < len; i++) {
                    const t = this.timestamps[i];
                    const nextT = this.timestamps[i + 1];
                    const startSec = (t.fromMs / totalRefMs) * adjDur;
                    const nextStartSec = nextT ? ((nextT.fromMs / totalRefMs) * adjDur) : adjDur;

                    if (adjCur >= startSec && adjCur < (nextStartSec + 0.05)) {
                        matchedAyah = t.ayah;
                        break;
                    }
                }
            }
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

        const fetchPromise = this.fetchSurahTimestamps(this.currentSurahId, reciterSubfolder);
        const metaPromise = new Promise(resolve => {
            if (this.audio.readyState >= 1) resolve();
            else this.audio.addEventListener("loadedmetadata", resolve, { once: true });
        });

        Promise.all([fetchPromise, metaPromise]).then(([ts]) => {
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
        const targetId = `ayah-${this.currentAyahNumber}`;
        const target = document.getElementById(targetId);

        const applyHighlight = (el) => {
            const prevActive = document.querySelector(".ayah-card.active-playing");
            if (prevActive && prevActive !== el) {
                prevActive.classList.remove("active-playing");
            }

            if (el) {
                if (!el.classList.contains("active-playing")) {
                    el.classList.add("active-playing");
                }
                const rect = el.getBoundingClientRect();
                if (rect.top < 90 || rect.bottom > window.innerHeight - 90) {
                    el.scrollIntoView({ behavior: "smooth", block: "center" });
                }
            }
        };

        if (target) {
            applyHighlight(target);
        } else {
            // Verse card element might still be rendering in DOM by quran-reader.js
            setTimeout(() => {
                const retryEl = document.getElementById(targetId);
                if (retryEl) applyHighlight(retryEl);
            }, 250);
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
        if (bar) {
            bar.classList.remove("hidden-bar");
            document.body.classList.add("has-audio-bar");
        }

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
