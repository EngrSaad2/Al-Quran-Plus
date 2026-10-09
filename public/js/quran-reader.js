/**
 * Quran Mazid - Surah Reader Engine
 * Dynamic verse rendering, multi-translations, WBW, caching & bookmarks
 */

class QuranReader {
    constructor(surahId, targetAyah = null) {
        this.surahId = parseInt(surahId) || 1;
        this.targetAyah = parseInt(targetAyah) || null;
        this.surahMeta = null;
        this.verses = [];
        this.showBangla = true;
        this.showEnglish = true;
        this.showWbw = false;
        this.arabicFontSize = 25;
        this.transFontSize = 15;
        this.arabicFont = "font-amiri"; // 'font-amiri', 'font-scheherazade', 'font-naskh'

        this.init();
    }

    init() {
        if (window.QURAN_DATA) {
            this.surahMeta = window.QURAN_DATA.surahs.find(s => s.id === this.surahId) || window.QURAN_DATA.surahs[0];
        }

        this.loadSettings();
        this.fetchSurahVerses();
        this.bindEvents();
    }

    loadSettings() {
        const savedArabicSize = localStorage.getItem("quran_arabic_size");
        const savedTransSize = localStorage.getItem("quran_trans_size");
        const savedArabicFont = localStorage.getItem("quran_arabic_font");
        const savedShowBn = localStorage.getItem("quran_show_bn");
        const savedShowEn = localStorage.getItem("quran_show_en");
        const savedShowWbw = localStorage.getItem("quran_show_wbw");
        const currentLang = window.currentQuranLang || localStorage.getItem("quran_lang") || "bn";

        if (savedArabicSize) {
            this.arabicFontSize = parseInt(savedArabicSize);
        } else {
            this.arabicFontSize = 25;
        }
        if (savedTransSize) this.transFontSize = parseInt(savedTransSize);
        if (savedArabicFont) this.arabicFont = savedArabicFont;
        if (savedShowBn !== null) {
            this.showBangla = savedShowBn === "true";
        } else {
            this.showBangla = currentLang === "bn";
        }
        if (savedShowEn !== null) {
            this.showEnglish = savedShowEn === "true";
        } else {
            this.showEnglish = currentLang !== "bn";
        }
        if (savedShowWbw !== null) this.showWbw = savedShowWbw === "true";
    }

    async fetchSurahVerses() {
        const container = document.getElementById("ayahsContainer");
        const cacheKey = `quran_surah_v2_${this.surahId}`;
        const cached = localStorage.getItem(cacheKey);

        const lang = window.currentQuranLang || localStorage.getItem("quran_lang") || "bn";
        const isBn = lang === "bn";
        const surahTitle = isBn ? (this.surahMeta ? this.surahMeta.bangla : '') : (this.surahMeta ? this.surahMeta.name : '');
        const loadingText = isBn ? `সূরা ${surahTitle} লোড হচ্ছে...` : `Loading Surah ${surahTitle}...`;

        if (cached) {
            try {
                this.verses = JSON.parse(cached);
                this.renderVerses();
                return;
            } catch (e) {
                console.warn("Invalid cached verses, fetching online...", e);
            }
        }

        if (container) {
            container.innerHTML = `
                <div style="text-align:center; padding: 60px 0; color: var(--text-muted);">
                    <i class="fa-solid fa-spinner fa-spin fa-2x" style="color: var(--primary-color);"></i>
                    <p style="margin-top: 15px; font-size: 0.95rem;">${loadingText}</p>
                </div>
            `;
        }

        try {
            const res = await fetch(`https://api.alquran.cloud/v1/surah/${this.surahId}/editions/quran-uthmani,bn.bengali,en.sahih`);
            const data = await res.json();

            if (data.code === 200 && data.data && data.data.length >= 3) {
                const arabicData = data.data[0].ayahs;
                const banglaData = data.data[1].ayahs;
                const englishData = data.data[2].ayahs;

                this.verses = arabicData.map((ayah, idx) => {
                    let arText = ayah.text;
                    // Remove Bismillah from verse 1 for surahs other than Al-Fatiha
                    if (this.surahId !== 1 && ayah.numberInSurah === 1) {
                        arText = arText.replace("بِسْمِ ٱللَّهِ ٱلرَّحْمَٰنِ ٱلرَّحِيمِ", "").trim();
                    }

                    return {
                        number: ayah.numberInSurah,
                        globalNumber: ayah.number,
                        arabic: arText,
                        bangla: banglaData[idx] ? banglaData[idx].text : "",
                        english: englishData[idx] ? englishData[idx].text : ""
                    };
                });

                // Cache for fast offline loading
                localStorage.setItem(cacheKey, JSON.stringify(this.verses));
                this.renderVerses();
            } else {
                throw new Error("Failed to parse API response");
            }
        } catch (err) {
            console.error("Error loading Surah:", err);
            if (container) {
                const errTitle = isBn ? "ইন্টারনেট সংযোগ চেক করুন" : "Check Internet Connection";
                const errSub = isBn ? "আয়াত লোড করতে সমস্যা হয়েছে। অনুগ্রহ করে পুনরায় চেষ্টা করুন।" : "Failed to load verses. Please check your internet connection.";
                const retryBtn = isBn ? "পুনরায় চেষ্টা করুন" : "Try Again";
                container.innerHTML = `
                    <div style="text-align:center; padding: 50px 20px; background: var(--bg-card); border-radius: 18px; border: 1px solid var(--border-color);">
                        <i class="fa-solid fa-triangle-exclamation fa-2x text-warning"></i>
                        <h5 style="margin-top: 12px; color: var(--text-primary);">${errTitle}</h5>
                        <p style="color: var(--text-muted); font-size: 0.85rem;">${errSub}</p>
                        <button onclick="window.location.reload()" class="btn-quran-primary" style="margin-top: 10px; padding: 8px 20px; font-size: 0.85rem;">${retryBtn}</button>
                    </div>
                `;
            }
        }
    }

    renderVerses() {
        const container = document.getElementById("ayahsContainer");
        if (!container) return;

        const lang = window.currentQuranLang || localStorage.getItem("quran_lang") || "bn";
        const isBn = lang === "bn";
        let html = "";

        this.verses.forEach(v => {
            const isBookmarked = this.isAyahBookmarked(this.surahId, v.number);
            const ayahNumFormatted = isBn && window.toBanglaNumber ? `${window.toBanglaNumber(this.surahId)}:${window.toBanglaNumber(v.number)}` : `${this.surahId}:${v.number}`;

            html += `
                <div class="ayah-card" id="ayah-${v.number}">
                    <div class="ayah-card-header">
                        <span class="ayah-number-badge">${ayahNumFormatted}</span>
                        <div class="ayah-actions-group">
                            <button class="btn-ayah-action" title="${isBn ? 'শুনুন' : 'Listen'}" onclick="window.quranPlayer.playAyah(${this.surahId}, ${v.number}, ${this.verses.length})">
                                <i class="fa-solid fa-play"></i>
                            </button>
                            <button class="btn-ayah-action ${isBookmarked ? 'text-emerald' : ''}" title="${isBn ? 'বুকমার্ক' : 'Bookmark'}" onclick="window.quranReader.toggleBookmark(${this.surahId}, ${v.number}, this)">
                                <i class="${isBookmarked ? 'fa-solid' : 'fa-regular'} fa-bookmark"></i>
                            </button>
                            <button class="btn-ayah-action" title="${isBn ? 'কপি করুন' : 'Copy'}" onclick="window.quranReader.copyAyah(${v.number})">
                                <i class="fa-regular fa-copy"></i>
                            </button>
                            <button class="btn-ayah-action" title="${isBn ? 'শেয়ার' : 'Share'}" onclick="window.quranReader.shareAyah(${v.number})">
                                <i class="fa-solid fa-share-nodes"></i>
                            </button>
                        </div>
                    </div>

                    <div class="ayah-arabic-text ${this.arabicFont}" style="font-size: ${this.arabicFontSize}px;">
                        ${v.arabic}
                        <span style="font-family:'Amiri', serif; color: var(--primary-color); font-size:0.9em; margin-right: 8px;">﴿${this.toArabicNumerals(v.number)}﴾</span>
                    </div>

                    <div class="ayah-translations-container">
                        ${this.showBangla && v.bangla ? `
                            <div class="ayah-translation-item bangla" style="font-size: ${this.transFontSize}px;">
                                <div class="translation-tag">${isBn ? 'বাংলা অনুবাদ' : 'Bengali Translation'}</div>
                                ${v.bangla}
                            </div>
                        ` : ''}

                        ${this.showEnglish && v.english ? `
                            <div class="ayah-translation-item english" style="font-size: ${this.transFontSize}px;">
                                <div class="translation-tag">${isBn ? 'English (Sahih Int.)' : 'English (Sahih International)'}</div>
                                ${v.english}
                            </div>
                        ` : ''}
                    </div>
                </div>
            `;
        });

        // Bottom Next / Prev Surah navigation footer
        const prevId = this.surahId > 1 ? this.surahId - 1 : null;
        const nextId = this.surahId < 114 ? this.surahId + 1 : null;
        const baseUrl = window.APP_BASE_URL || '';

        html += `
            <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 40px; padding: 20px 0; border-top: 1px solid var(--border-color);">
                ${prevId ? `
                    <a href="${baseUrl}/surah/${prevId}" class="btn-quran-outline" style="text-decoration:none; display:flex; align-items:center; gap:8px;">
                        <i class="fa-solid fa-arrow-left"></i> ${isBn ? 'পূর্ববর্তী সূরা' : 'Previous Surah'}
                    </a>
                ` : '<div></div>'}

                ${nextId ? `
                    <a href="${baseUrl}/surah/${nextId}" class="btn-quran-primary" style="text-decoration:none; display:flex; align-items:center; gap:8px;">
                        ${isBn ? 'পরবর্তী সূরা' : 'Next Surah'} <i class="fa-solid fa-arrow-right"></i>
                    </a>
                ` : '<div></div>'}
            </div>
        `;

        container.innerHTML = html;

        // Auto highlight & scroll target ayah if provided in shared link
        if (this.targetAyah) {
            setTimeout(() => {
                const targetCard = document.getElementById(`ayah-${this.targetAyah}`);
                if (targetCard) {
                    document.querySelectorAll(".ayah-card.active-playing, .ayah-card.shared-highlight").forEach(el => {
                        el.classList.remove("active-playing", "shared-highlight");
                    });
                    targetCard.classList.add("active-playing", "shared-highlight");
                    targetCard.scrollIntoView({ behavior: "smooth", block: "center" });
                }
            }, 300);
        } else if (window.quranPlayer) {
            window.quranPlayer.highlightActiveAyah();
        }
    }

    toArabicNumerals(num) {
        const arabicNumbers = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        return String(num).split('').map(digit => arabicNumbers[parseInt(digit)] || digit).join('');
    }

    toggleBookmark(surahId, ayahNumber, btn) {
        let bookmarks = JSON.parse(localStorage.getItem("quran_bookmarks") || "[]");
        const existsIdx = bookmarks.findIndex(b => b.surah === surahId && b.ayah === ayahNumber);

        if (existsIdx > -1) {
            bookmarks.splice(existsIdx, 1);
            if (btn) {
                btn.classList.remove("text-emerald");
                btn.innerHTML = '<i class="fa-regular fa-bookmark"></i>';
            }
        } else {
            const sMeta = window.QURAN_DATA ? window.QURAN_DATA.surahs.find(s => s.id === surahId) : null;
            bookmarks.push({
                surah: surahId,
                ayah: ayahNumber,
                surahName: sMeta ? sMeta.name : `Surah ${surahId}`,
                banglaName: sMeta ? sMeta.bangla : `সূরা ${surahId}`,
                date: new Date().toLocaleDateString('en-CA')
            });
            if (btn) {
                btn.classList.add("text-emerald");
                btn.innerHTML = '<i class="fa-solid fa-bookmark"></i>';
            }
        }

        localStorage.setItem("quran_bookmarks", JSON.stringify(bookmarks));
    }

    isAyahBookmarked(surahId, ayahNumber) {
        const bookmarks = JSON.parse(localStorage.getItem("quran_bookmarks") || "[]");
        return bookmarks.some(b => b.surah === surahId && b.ayah === ayahNumber);
    }

    copyAyah(ayahNumber) {
        const v = this.verses.find(item => item.number === ayahNumber);
        if (!v) return;

        const lang = window.currentQuranLang || localStorage.getItem("quran_lang") || "bn";
        const isBn = lang === "bn";
        const sName = isBn ? (this.surahMeta ? `${this.surahMeta.bangla} (${this.surahMeta.name})` : `সূরা ${this.surahId}`) : (this.surahMeta ? `${this.surahMeta.name} (${this.surahMeta.englishMeaning})` : `Surah ${this.surahId}`);
        const copyText = isBn 
            ? `${v.arabic}\n\nবাংলা: ${v.bangla}\n\nEnglish: ${v.english}\n\n[সূরা ${sName}, আয়াত ${ayahNumber}]`
            : `${v.arabic}\n\nEnglish: ${v.english}\n\n[${sName}, Verse ${ayahNumber}]`;

        navigator.clipboard.writeText(copyText).then(() => {
            alert(isBn ? `আয়াত ${this.surahId}:${ayahNumber} কপি করা হয়েছে!` : `Verse ${this.surahId}:${ayahNumber} copied to clipboard!`);
        });
    }

    shareAyah(ayahNumber) {
        const v = this.verses.find(item => item.number === ayahNumber);
        if (!v) return;

        const lang = window.currentQuranLang || localStorage.getItem("quran_lang") || "bn";
        const isBn = lang === "bn";
        const sTitle = isBn ? (this.surahMeta ? this.surahMeta.bangla : this.surahId) : (this.surahMeta ? this.surahMeta.name : this.surahId);
        const prefix = isBn ? "সূরা" : "Surah";
        const verseWord = isBn ? "আয়াত" : "Verse";
        const shareUrl = `${window.location.origin}/surah/${this.surahId}?ayah=${ayahNumber}&autoplay=1`;
        const shareText = `${v.arabic}\n\n${isBn ? v.bangla : v.english}\n\n[${prefix} ${sTitle}, ${verseWord} ${ayahNumber}]\n${shareUrl}`;

        if (navigator.share) {
            navigator.share({
                title: `${prefix} ${sTitle}, ${verseWord} ${ayahNumber}`,
                text: shareText,
                url: shareUrl
            }).catch(() => {});
        } else {
            navigator.clipboard.writeText(shareText).then(() => {
                alert(isBn ? `আয়াত ${this.surahId}:${ayahNumber} লিংক কপি করা হয়েছে!` : `Verse ${this.surahId}:${ayahNumber} link copied!`);
            });
        }
    }

    bindEvents() {
        // Settings sliders
        const arabicSizeSlider = document.getElementById("settingArabicSize");
        const transSizeSlider = document.getElementById("settingTransSize");
        const arabicFontSelect = document.getElementById("settingArabicFont");
        const showBnCheck = document.getElementById("settingShowBangla");
        const showEnCheck = document.getElementById("settingShowEnglish");

        if (arabicSizeSlider) {
            arabicSizeSlider.value = this.arabicFontSize;
            arabicSizeSlider.addEventListener("input", (e) => {
                this.arabicFontSize = parseInt(e.target.value);
                localStorage.setItem("quran_arabic_size", this.arabicFontSize);
                document.querySelectorAll(".ayah-arabic-text").forEach(el => {
                    el.style.fontSize = `${this.arabicFontSize}px`;
                });
                const lbl = document.getElementById("arabicSizeValue");
                if (lbl) lbl.textContent = `${this.arabicFontSize}px`;
            });
        }

        if (transSizeSlider) {
            transSizeSlider.value = this.transFontSize;
            transSizeSlider.addEventListener("input", (e) => {
                this.transFontSize = parseInt(e.target.value);
                localStorage.setItem("quran_trans_size", this.transFontSize);
                document.querySelectorAll(".ayah-translation-item").forEach(el => {
                    el.style.fontSize = `${this.transFontSize}px`;
                });
                const lbl = document.getElementById("transSizeValue");
                if (lbl) lbl.textContent = `${this.transFontSize}px`;
            });
        }

        if (arabicFontSelect) {
            arabicFontSelect.value = this.arabicFont;
            arabicFontSelect.addEventListener("change", (e) => {
                this.arabicFont = e.target.value;
                localStorage.setItem("quran_arabic_font", this.arabicFont);
                document.querySelectorAll(".ayah-arabic-text").forEach(el => {
                    el.className = `ayah-arabic-text ${this.arabicFont}`;
                });
            });
        }

        if (showBnCheck) {
            showBnCheck.checked = this.showBangla;
            showBnCheck.addEventListener("change", (e) => {
                this.showBangla = e.target.checked;
                localStorage.setItem("quran_show_bn", this.showBangla);
                this.renderVerses();
            });
        }

        if (showEnCheck) {
            showEnCheck.checked = this.showEnglish;
            showEnCheck.addEventListener("change", (e) => {
                this.showEnglish = e.target.checked;
                localStorage.setItem("quran_show_en", this.showEnglish);
                this.renderVerses();
            });
        }

        window.addEventListener("quranLanguageChanged", (e) => {
            const nextLang = e.detail?.lang || window.currentQuranLang || "bn";
            if (localStorage.getItem("quran_show_bn") === null) {
                this.showBangla = nextLang === "bn";
                if (showBnCheck) showBnCheck.checked = this.showBangla;
            }
            if (localStorage.getItem("quran_show_en") === null) {
                this.showEnglish = nextLang !== "bn";
                if (showEnCheck) showEnCheck.checked = this.showEnglish;
            }
            this.renderVerses();
        });
    }
}

