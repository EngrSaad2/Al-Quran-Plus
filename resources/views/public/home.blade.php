@extends('layouts.public')

@section('meta')
    @include('partials.seo-meta', [
        'metaTitle' => 'Al Quran — Read & Listen Holy Quran Online with Bangla & English Translation | Al Quran Plus',
        'metaDesc' => 'Read, listen, and explore the Holy Quran online with crystal clear Arabic script, authentic English and Bangla translations, verse-by-verse recitation audio, Tafsir, Dua, and prayer times.',
        'metaKeywords' => 'Al Quran, Holy Quran Online, Quran English Translation, Quran Bangla Translation, Quran Audio Recitation, Read Quran Online, Surah Yaseen, Surah Rahman, Quran Tafsir, Islamic Dua, Al Quran Plus',
        'canonicalUrl' => url('/'),
        'ogType' => 'website',
        'ogImage' => asset('images/seo-og-banner.png'),
        'websiteSchema' => $websiteSchema ?? null,
        'orgSchema' => $orgSchema ?? null
    ])
@endsection

@section('content')

<!-- Hero Section -->
<section class="hero-wrapper">
    <!-- Bismillah Calligraphy -->
    <p class="hero-bismillah">بِسْمِ ٱللَّٰهِ ٱلرَّحْمَٰنِ ٱلرَّحِيمِ</p>

    <!-- Large Al Quran Heading -->
    <h1 class="hero-title">Al Quran</h1>

    <!-- Bengali / English Subtitle with Swoosh Curve -->
    <div class="hero-sub-bengali">
        <span id="heroSubBnSpan">পড়ো তোমার প্রতিপালকের নামে, যিনি সৃষ্টি করেছেন</span>
        <svg viewBox="0 0 220 14" fill="none" class="hero-swoosh">
            <path d="M4 9 C 62 1, 120 15, 216 5" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"></path>
        </svg>
    </div>

    <!-- English Subtitle with Diamond Dividers -->
    <div class="hero-sub-english">
        <span class="line"></span>
        <span class="dot"></span>
        <span id="heroSubEnSpan">READ IN THE NAME OF YOUR LORD WHO CREATED</span>
        <span class="dot"></span>
        <span class="line right"></span>
    </div>

    <!-- 3D Fan / Stacked Cards Carousel -->
    <div class="cards-fan-container">
        <div class="cards-fan-stack" id="featuredFanStack">
            <a href="{{ route('public.surah', 112) }}?autoplay=1" class="fan-card-item" style="transform: translateY(22px) rotate(-10deg); z-index: 10;">
                <div class="fan-card-inner">
                    <span class="fan-card-bg-watermark">سورة</span>
                    <div class="fan-card-top">
                        <span class="badge-surah-num">112</span>
                        <button class="fan-card-quick-play" onclick="playFanCardAudio(event, 112)" title="শুনুন">
                            <i class="fa-solid fa-play"></i>
                        </button>
                        <span class="badge-surah-origin">🕋 মাক্কী</span>
                    </div>
                    <div class="fan-card-arabic">الإخلاص</div>
                    <div class="fan-card-bottom">
                        <h4 class="fan-card-name font-bangla">আল-ইখলাস</h4>
                        <p class="fan-card-verses">৪ আয়াত</p>
                    </div>
                </div>
            </a>
            <a href="{{ route('public.surah', 18) }}?autoplay=1" class="fan-card-item" style="transform: translateY(-6px) rotate(-5deg); z-index: 20;">
                <div class="fan-card-inner">
                    <span class="fan-card-bg-watermark">سورة</span>
                    <div class="fan-card-top">
                        <span class="badge-surah-num">18</span>
                        <button class="fan-card-quick-play" onclick="playFanCardAudio(event, 18)" title="শুনুন">
                            <i class="fa-solid fa-play"></i>
                        </button>
                        <span class="badge-surah-origin">🕋 মাক্কী</span>
                    </div>
                    <div class="fan-card-arabic">الكهف</div>
                    <div class="fan-card-bottom">
                        <h4 class="fan-card-name font-bangla">আল-কাহফ</h4>
                        <p class="fan-card-verses">১১০ আয়াত</p>
                    </div>
                </div>
            </a>
            <a href="{{ route('public.surah', 55) }}?autoplay=1" class="fan-card-item" style="transform: translateY(-34px) rotate(0deg); z-index: 30;">
                <div class="fan-card-inner">
                    <span class="fan-card-bg-watermark">سورة</span>
                    <div class="fan-card-top">
                        <span class="badge-surah-num">55</span>
                        <button class="fan-card-quick-play" onclick="playFanCardAudio(event, 55)" title="শুনুন">
                            <i class="fa-solid fa-play"></i>
                        </button>
                        <span class="badge-surah-origin">🕌 মাদানী</span>
                    </div>
                    <div class="fan-card-arabic">الرحمن</div>
                    <div class="fan-card-bottom">
                        <h4 class="fan-card-name font-bangla">আর-রহমান</h4>
                        <p class="fan-card-verses">৭৮ আয়াত</p>
                    </div>
                </div>
            </a>
            <a href="{{ route('public.surah', 67) }}?autoplay=1" class="fan-card-item" style="transform: translateY(-6px) rotate(5deg); z-index: 20;">
                <div class="fan-card-inner">
                    <span class="fan-card-bg-watermark">سورة</span>
                    <div class="fan-card-top">
                        <span class="badge-surah-num">67</span>
                        <button class="fan-card-quick-play" onclick="playFanCardAudio(event, 67)" title="শুনুন">
                            <i class="fa-solid fa-play"></i>
                        </button>
                        <span class="badge-surah-origin">🕋 মাক্কী</span>
                    </div>
                    <div class="fan-card-arabic">الملك</div>
                    <div class="fan-card-bottom">
                        <h4 class="fan-card-name font-bangla">আল-মুলক</h4>
                        <p class="fan-card-verses">৩০ আয়াত</p>
                    </div>
                </div>
            </a>
            <a href="{{ route('public.surah', 1) }}?autoplay=1" class="fan-card-item" style="transform: translateY(22px) rotate(10deg); z-index: 10;">
                <div class="fan-card-inner">
                    <span class="fan-card-bg-watermark">سورة</span>
                    <div class="fan-card-top">
                        <span class="badge-surah-num">1</span>
                        <button class="fan-card-quick-play" onclick="playFanCardAudio(event, 1)" title="শুনুন">
                            <i class="fa-solid fa-play"></i>
                        </button>
                        <span class="badge-surah-origin">🕋 মাক্কী</span>
                    </div>
                    <div class="fan-card-arabic">الفاتحة</div>
                    <div class="fan-card-bottom">
                        <h4 class="fan-card-name font-bangla">আল-ফাতিহা</h4>
                        <p class="fan-card-verses">৭ আয়াত</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Hero Search Bar -->
    <form class="hero-search-container" action="{{ route('public.search') }}" method="GET">
        <div class="hero-search-box">
            <i class="fa-solid fa-magnifying-glass text-muted me-2"></i>
            <input type="text" name="q" id="homeSearchInput" class="hero-search-input" placeholder="সূরার নাম, নম্বর, বা আয়াত দিয়ে খুঁজুন..." autocomplete="off">
            <button type="submit" class="hero-search-btn" id="homeSearchBtn" title="Search">
                <i class="fa-solid fa-arrow-right"></i>
            </button>
        </div>
    </form>
</section>

<!-- Last Played Widget -->
<div class="last-played-wrapper">
    <div class="last-played-card" id="lastPlayedCard" onclick="playLastPlayed()">
        <div class="last-played-left">
            <div class="last-played-icon" id="lastPlayedArabic">الكهف</div>
            <div class="last-played-info">
                <div class="last-played-badge" id="lastPlayedBadge">
                    <span class="pulse-dot"></span> <span id="lastPlayedBadgeText">সর্বশেষ শোনা হয়েছে</span>
                </div>
                <h4 class="last-played-title" id="lastPlayedName">সূরা আল-কাহফ</h4>
                <p class="last-played-verse" id="lastPlayedVerse">আয়াত ১ · চালিয়ে যান</p>
            </div>
        </div>
        <button class="last-played-playbtn" title="চালান">
            <i class="fa-solid fa-play"></i>
        </button>
    </div>
</div>

<!-- Popular Recitations Section -->
<section class="popular-recitations-container">
    <div class="section-box-container">
        <div class="section-header-row">
            <div class="section-header-left">
                <div class="section-icon-badge">
                    <i class="fa-solid fa-fire-flame-curved"></i>
                </div>
                <div>
                    <h2 class="section-header-title" id="popularSectionTitle">জনপ্রিয় তিলাওয়াত</h2>
                    <p class="section-header-subtitle" id="popularSectionSub">সবচেয়ে বেশি শোনা সূরা</p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-secondary text-light px-3 py-2 rounded-pill font-bangla border border-secondary" id="popularSectionBadge" style="font-size:0.75rem;">
                    <i class="fa-solid fa-star text-warning me-1"></i> সেরা তিলাওয়াত
                </span>
                <button class="btn-icon-circle" id="popularShuffleBtn" onclick="shufflePopularRecitations()" title="নতুন সূরা দেখুন">
                    <i class="fa-solid fa-shuffle"></i>
                </button>
            </div>
        </div>

        <div class="recitation-grid" id="popularRecitationsGrid">
            <!-- Dynamically populated from QURAN_DATA.popularRecitations -->
        </div>
    </div>
</section>

<!-- Surahs / Juz / Pages Tabs & Filters -->
<section class="explorer-tabs-container">
    <div class="explorer-header-bar">
        <!-- Main Navigation Tabs -->
        <div class="nav-main-tabs">
            <button class="main-tab-btn active" id="tabSurahsBtn" onclick="switchMainTab('surahs')">
                <i class="fa-solid fa-book-open"></i> সূরা (১১৪)
            </button>
            <button class="main-tab-btn" id="tabJuzBtn" onclick="switchMainTab('juz')">
                <i class="fa-solid fa-layer-group"></i> ৩০ পারা
            </button>
            <button class="main-tab-btn" id="tabPagesBtn" onclick="switchMainTab('pages')">
                <i class="fa-solid fa-file-lines"></i> পৃষ্ঠা
            </button>
        </div>

        <!-- Filter Chips (Surahs tab only) -->
        <div class="filter-chips-group" id="surahFilterChips">
            <button class="filter-chip-btn active" data-filter="all" onclick="filterSurahs('all', this)">
                <i class="fa-solid fa-list-check"></i> সব
            </button>
            <button class="filter-chip-btn" data-filter="Makki" onclick="filterSurahs('Makki', this)">
                <i class="fa-solid fa-kaaba text-warning"></i> মাক্কী
            </button>
            <button class="filter-chip-btn" data-filter="Madani" onclick="filterSurahs('Madani', this)">
                <i class="fa-solid fa-mosque text-emerald"></i> মাদানী
            </button>
            <button class="filter-chip-btn" data-filter="saved" onclick="filterSurahs('saved', this)">
                <i class="fa-solid fa-bookmark text-primary"></i> সংরক্ষিত
            </button>
        </div>
    </div>
</section>

<!-- Surah Cards Grid -->
<section class="surah-grid-container">
    <div class="surah-cards-grid" id="surahCardsGrid">
        <!-- Populated by JavaScript from QURAN_DATA.surahs -->
    </div>

    <!-- 30 Juz Grid (Hidden initially) -->
    <div class="surah-cards-grid d-none" id="juzCardsGrid">
        <!-- Populated by JavaScript -->
    </div>

    <!-- Pages Grid (Hidden initially) -->
    <div class="surah-cards-grid d-none" id="pagesCardsGrid">
        <!-- Populated by JavaScript -->
    </div>

    <!-- Explore All 114 Surahs Button -->
    <div class="explore-all-btn-container" id="exploreAllContainer">
        <button class="btn-explore-all" onclick="showAllSurahs()">
            সকল ১১৪ সূরা দেখুন <i class="fa-solid fa-angle-down ms-1"></i>
        </button>
    </div>
</section>

@endsection

@section('scripts')
<script>
    let activeFilter = 'all';
    let isShowingAll = false;
    const INITIAL_LIMIT = 18;

    function safeToBn(num) {
        if (num === null || num === undefined) return '';
        if (typeof window.toBanglaNumber === 'function') {
            return window.toBanglaNumber(num);
        }
        const bnNums = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
        return String(num).split('').map(d => bnNums[parseInt(d)] !== undefined ? bnNums[parseInt(d)] : d).join('');
    }

    document.addEventListener("DOMContentLoaded", () => {
        updateHomeLanguageUI();
    });

    // Listen to global language changes
    window.addEventListener("quranLanguageChanged", () => {
        updateHomeLanguageUI();
    });

    function updateHomeLanguageUI() {
        const lang = window.currentQuranLang || localStorage.getItem("quran_lang") || "bn";
        const isBn = lang === "bn";

        // Hero Subtitle
        const heroSubBn = document.getElementById("heroSubBnSpan");
        const heroSubEn = document.getElementById("heroSubEnSpan");
        if (heroSubBn) {
            heroSubBn.textContent = isBn ? "পড়ো তোমার প্রতিপালকের নামে, যিনি সৃষ্টি করেছেন" : "Read in the name of your Lord who created";
            if (isBn) {
                heroSubBn.parentElement.classList.add("font-bangla");
            } else {
                heroSubBn.parentElement.classList.remove("font-bangla");
            }
        }
        if (heroSubEn) {
            heroSubEn.textContent = isBn ? "READ IN THE NAME OF YOUR LORD WHO CREATED" : "THE NOBLE QURAN — RECITATION & TRANSLATIONS";
        }

        // Hero Search
        const searchInput = document.getElementById("homeSearchInput");
        if (searchInput) {
            searchInput.placeholder = isBn ? "সূরার নাম, নম্বর বা আয়াত দিয়ে খুঁজুন..." : "Search by Surah name, number, or verse...";
        }

        // Section header for Popular Recitations
        const popTitle = document.getElementById("popularSectionTitle");
        const popSub = document.getElementById("popularSectionSub");
        const popBadge = document.getElementById("popularSectionBadge");
        const popShuffle = document.getElementById("popularShuffleBtn");
        if (popTitle) popTitle.textContent = isBn ? "জনপ্রিয় তিলাওয়াত" : "Popular Recitations";
        if (popSub) popSub.textContent = isBn ? "সবচেয়ে বেশি শোনা সূরা" : "Most listened Surahs worldwide";
        if (popBadge) popBadge.innerHTML = `<i class="fa-solid fa-star text-warning me-1"></i> ${isBn ? "সেরা তিলাওয়াত" : "Top Recitations"}`;
        if (popShuffle) popShuffle.title = isBn ? "নতুন সূরা দেখুন" : "Shuffle Recitations";

        // Tabs
        const tabSurahs = document.getElementById("tabSurahsBtn");
        const tabJuz = document.getElementById("tabJuzBtn");
        const tabPages = document.getElementById("tabPagesBtn");
        if (tabSurahs) tabSurahs.innerHTML = `<i class="fa-solid fa-book-open"></i> ${isBn ? "সূরা (১১৪)" : "Surahs (114)"}`;
        if (tabJuz) tabJuz.innerHTML = `<i class="fa-solid fa-layer-group"></i> ${isBn ? "৩০ পারা" : "30 Juz"}`;
        if (tabPages) tabPages.innerHTML = `<i class="fa-solid fa-file-lines"></i> ${isBn ? "পৃষ্ঠা" : "Pages"}`;

        // Filter chips
        const chipAll = document.querySelector('[data-filter="all"]');
        const chipMakki = document.querySelector('[data-filter="Makki"]');
        const chipMadani = document.querySelector('[data-filter="Madani"]');
        const chipSaved = document.querySelector('[data-filter="saved"]');
        if (chipAll) chipAll.innerHTML = `<i class="fa-solid fa-list-check"></i> ${isBn ? "সব" : "All"}`;
        if (chipMakki) chipMakki.innerHTML = `<i class="fa-solid fa-kaaba text-warning"></i> ${isBn ? "মাক্কী" : "Makki"}`;
        if (chipMadani) chipMadani.innerHTML = `<i class="fa-solid fa-mosque text-emerald"></i> ${isBn ? "মাদানী" : "Madani"}`;
        if (chipSaved) chipSaved.innerHTML = `<i class="fa-solid fa-bookmark text-primary"></i> ${isBn ? "সংরক্ষিত" : "Saved"}`;

        // Explore all button
        const exploreBtn = document.querySelector(".btn-explore-all");
        if (exploreBtn) exploreBtn.innerHTML = `${isBn ? "সকল ১১৪ সূরা দেখুন" : "Explore All 114 Surahs"} <i class="fa-solid fa-angle-down ms-1"></i>`;

        // Footer
        const footerBrand = document.getElementById("footerBrand");
        if (footerBrand) footerBrand.textContent = isBn ? "আল কুরআন" : "Al Quran";
        const footerDesc = document.getElementById("footerDesc");
        if (footerDesc) footerDesc.textContent = isBn ? "পবিত্র কুরআন পাঠ ও শোনার জন্য একটি পরিচ্ছন্ন, বিজ্ঞাপনহীন ইসলামিক ওয়েব প্ল্যাটফর্ম।" : "A clean, ad-free Islamic web platform for reading and listening to the Holy Quran.";
        const footerRead = document.getElementById("footerRead");
        const footerBookmarks = document.getElementById("footerBookmarks");
        const footerSearch = document.getElementById("footerSearch");
        const footerPrivacy = document.getElementById("footerPrivacy");
        const footerContact = document.getElementById("footerContact");
        if (footerRead) footerRead.textContent = isBn ? "কুরআন পড়ুন" : "Read Quran";
        if (footerBookmarks) footerBookmarks.textContent = isBn ? "বুকমার্ক" : "Bookmarks";
        if (footerSearch) footerSearch.textContent = isBn ? "অনুসন্ধান" : "Search";
        if (footerPrivacy) footerPrivacy.textContent = isBn ? "প্রাইভেসি পলিসি" : "Privacy Policy";
        if (footerContact) footerContact.textContent = isBn ? "যোগাযোগ" : "Contact";

        // Render dynamic parts with error isolation
        try { renderFeaturedFanStack(); } catch (e) { console.error("renderFeaturedFanStack:", e); }
        try { renderPopularRecitations(); } catch (e) { console.error("renderPopularRecitations:", e); }
        try { renderSurahsGrid(); } catch (e) { console.error("renderSurahsGrid:", e); }
        try { updateLastPlayedWidget(); } catch (e) { console.error("updateLastPlayedWidget:", e); }
        try { renderJuzGrid(); } catch (e) { console.error("renderJuzGrid:", e); }
        try { renderPagesGrid(); } catch (e) { console.error("renderPagesGrid:", e); }
    }

    // 1. Render 3D Fan / Stacked Cards
    function renderFeaturedFanStack() {
        const container = document.getElementById("featuredFanStack");
        if (!container || !window.QURAN_DATA || !window.QURAN_DATA.featuredStack) return;

        const lang = window.currentQuranLang || 'bn';
        const isBn = lang === 'bn';
        const stackItems = window.QURAN_DATA.featuredStack;
        const transforms = [
            'translateY(22px) rotate(-10deg)',
            'translateY(-6px) rotate(-5deg)',
            'translateY(-34px) rotate(0deg)',
            'translateY(-6px) rotate(5deg)',
            'translateY(22px) rotate(10deg)'
        ];
        const zIndexes = [10, 20, 30, 20, 10];

        const baseUrl = window.APP_BASE_URL || '';
        let html = "";
        stackItems.forEach((item, idx) => {
            const trans = transforms[idx] || 'translateY(0)';
            const z = zIndexes[idx] || 10;
            const icon = item.type === "Makki" ? "🕋" : "🕌";
            const badgeClass = item.type === "Makki" ? (isBn ? "মাক্কী" : "Makki") : (isBn ? "মাদানী" : "Madani");
            const displayName = isBn ? item.bangla : item.name;
            const displayVerses = isBn ? `${safeToBn(item.verses)} আয়াত` : `${item.verses} Verses`;

            html += `
                <a href="${baseUrl}/surah/${item.id}?autoplay=1" class="fan-card-item" style="transform: ${trans}; z-index: ${z};" data-card-idx="${idx}">
                    <div class="fan-card-inner">
                        <span class="fan-card-bg-watermark">سورة</span>
                        <div class="fan-card-top">
                            <span class="badge-surah-num">${item.id}</span>
                            <button class="fan-card-quick-play" onclick="playFanCardAudio(event, ${item.id})" title="${isBn ? 'তিলাওয়াত শুনুন' : 'Play Recitation'}">
                                <i class="fa-solid fa-play"></i>
                            </button>
                            <span class="badge-surah-origin">${icon} ${badgeClass}</span>
                        </div>
                        <div class="fan-card-arabic">${item.arabic}</div>
                        <div class="fan-card-bottom">
                            <h4 class="fan-card-name ${isBn ? 'font-bangla' : ''}">${displayName}</h4>
                            <p class="fan-card-verses">${displayVerses}</p>
                        </div>
                    </div>
                </a>
            `;
        });

        container.innerHTML = html;
    }

    function playFanCardAudio(e, surahId) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }
        const baseUrl = window.APP_BASE_URL || '';
        window.location.href = `${baseUrl}/surah/${surahId}?autoplay=1`;
    }

    // 2. Render Popular Recitations
    function renderPopularRecitations() {
        const grid = document.getElementById("popularRecitationsGrid");
        if (!grid || !window.QURAN_DATA) return;

        const lang = window.currentQuranLang || 'bn';
        const isBn = lang === 'bn';
        let recitations = [...window.QURAN_DATA.popularRecitations].slice(0, 4);
        let html = "";

        recitations.forEach((item, idx) => {
            const surah = window.QURAN_DATA.surahs.find(s => s.id === item.surahId) || { bangla: `সূরা ${item.surahId}`, name: `Surah ${item.surahId}`, arabic: '' };
            const title = isBn ? surah.bangla : surah.name;
            const listenersText = `${item.listeners} ${isBn ? 'শ্রোতা' : 'listeners'}`;

            html += `
                <div class="recitation-card" onclick="playPopularSurah(${item.surahId}, '${item.reciter}')">
                    <div>
                        <div class="recitation-card-top">
                            <span class="recitation-num-circle">${item.surahId}</span>
                            <button class="recitation-play-btn" title="${isBn ? 'শুনুন' : 'Listen'}">
                                <i class="fa-solid fa-play"></i>
                            </button>
                        </div>
                        <h4 class="recitation-title ${isBn ? 'font-bangla' : ''}">${title}</h4>
                        <p class="recitation-reciter">${item.reciter}</p>
                    </div>
                    <div class="recitation-footer">
                        <span><i class="fa-solid fa-headphones me-1"></i> ${listenersText}</span>
                        <span class="recitation-duration-badge">${item.duration}</span>
                    </div>
                </div>
            `;
        });

        grid.innerHTML = html;
    }

    function shufflePopularRecitations() {
        if (!window.QURAN_DATA) return;
        window.QURAN_DATA.popularRecitations.sort(() => Math.random() - 0.5);
        renderPopularRecitations();
    }

    function playPopularSurah(surahId, reciterName) {
        if (reciterName && window.QURAN_DATA) {
            const reciterObj = window.QURAN_DATA.reciters.find(r => r.name === reciterName);
            if (reciterObj) {
                localStorage.setItem("quran_reciter", reciterObj.subfolder);
                localStorage.setItem("quran_reciter_name", reciterObj.name);
            }
        }
        const baseUrl = window.APP_BASE_URL || '';
        window.location.href = `${baseUrl}/surah/${surahId}?autoplay=1`;
    }

    // 3. Render Surahs Grid
    function renderSurahsGrid() {
        const grid = document.getElementById("surahCardsGrid");
        if (!grid || !window.QURAN_DATA) return;

        const lang = window.currentQuranLang || 'bn';
        const isBn = lang === 'bn';
        const bookmarks = JSON.parse(localStorage.getItem("quran_bookmarks") || "[]");
        let list = window.QURAN_DATA.surahs;

        if (activeFilter === "Makki") {
            list = list.filter(s => s.type === "Makki");
        } else if (activeFilter === "Madani") {
            list = list.filter(s => s.type === "Madani");
        } else if (activeFilter === "saved") {
            const savedSurahIds = bookmarks.map(b => b.surah);
            list = list.filter(s => savedSurahIds.includes(s.id));
        }

        const displayList = isShowingAll ? list : list.slice(0, INITIAL_LIMIT);

        if (displayList.length === 0) {
            grid.innerHTML = `
                <div class="col-12 text-center py-5 text-muted" style="grid-column: 1 / -1;">
                    <i class="fa-regular fa-bookmark fa-2x mb-2 text-dim"></i>
                    <p class="mb-0">${isBn ? "কোন সংরক্ষিত সূরা পাওয়া যায়নি।" : "No saved Surahs found."}</p>
                </div>
            `;
            return;
        }

        const baseUrl = window.APP_BASE_URL || '';
        let html = "";
        displayList.forEach(s => {
            const isBookmarked = bookmarks.some(b => b.surah === s.id && !b.ayah);
            const icon = s.type === "Makki" ? "🕋" : "🕌";
            const badgeTypeClass = s.type === "Makki" ? "makki" : "madani";
            const badgeText = isBn ? (s.type === "Makki" ? "মাক্কী" : "মাদানী") : s.type;
            const surahTitle = isBn ? s.bangla : s.name;
            const surahSub = isBn ? s.banglaMeaning : s.englishMeaning;
            const versesText = isBn ? `${safeToBn(s.verses)} আয়াত` : `${s.verses} Verses`;

            html += `
                <a href="${baseUrl}/surah/${s.id}?autoplay=1" class="surah-card">
                    <div class="surah-card-left">
                        <div class="surah-num-box">${s.id}</div>
                        <div class="surah-card-names">
                            <h4 class="surah-name-en ${isBn ? 'font-bangla' : ''} mb-0">${surahTitle}</h4>
                            <p class="surah-meaning mb-0">${surahSub}</p>
                        </div>
                    </div>
                    <div class="surah-card-right">
                        <div class="surah-arabic-title">${s.arabic}</div>
                        <div class="surah-meta-row">
                            <span class="badge-origin-pill ${badgeTypeClass}">${icon} ${badgeText}</span>
                            <span class="surah-verses-count">${versesText}</span>
                            <button class="btn-bookmark-icon ${isBookmarked ? 'bookmarked' : ''}" onclick="event.preventDefault(); toggleSurahBookmark(${s.id}, this)" title="${isBn ? 'বুকমার্ক' : 'Bookmark'}">
                                <i class="${isBookmarked ? 'fa-solid' : 'fa-regular'} fa-bookmark"></i>
                            </button>
                        </div>
                    </div>
                </a>
            `;
        });

        grid.innerHTML = html;

        // Toggle explore all button visibility
        const exploreBtnContainer = document.getElementById("exploreAllContainer");
        if (exploreBtnContainer) {
            exploreBtnContainer.style.display = (list.length > INITIAL_LIMIT && !isShowingAll) ? "block" : "none";
        }
    }

    function filterSurahs(type, btn) {
        activeFilter = type;
        document.querySelectorAll(".filter-chip-btn").forEach(el => el.classList.remove("active"));
        if (btn) btn.classList.add("active");
        renderSurahsGrid();
    }

    function showAllSurahs() {
        isShowingAll = true;
        renderSurahsGrid();
    }

    function toggleSurahBookmark(surahId, btn) {
        let bookmarks = JSON.parse(localStorage.getItem("quran_bookmarks") || "[]");
        const existsIdx = bookmarks.findIndex(b => b.surah === surahId && !b.ayah);

        if (existsIdx > -1) {
            bookmarks.splice(existsIdx, 1);
            if (btn) {
                btn.classList.remove("bookmarked");
                btn.innerHTML = '<i class="fa-regular fa-bookmark"></i>';
            }
        } else {
            const s = window.QURAN_DATA.surahs.find(item => item.id === surahId);
            bookmarks.push({
                surah: surahId,
                surahName: s ? s.name : `Surah ${surahId}`,
                banglaName: s ? s.bangla : `সূরা ${surahId}`,
                date: new Date().toLocaleDateString('en-CA')
            });
            if (btn) {
                btn.classList.add("bookmarked");
                btn.innerHTML = '<i class="fa-solid fa-bookmark"></i>';
            }
        }

        localStorage.setItem("quran_bookmarks", JSON.stringify(bookmarks));
    }

    // 4. Tabs: Surahs, Juz, Pages
    function switchMainTab(tab) {
        document.querySelectorAll(".main-tab-btn").forEach(el => el.classList.remove("active"));
        const surahGrid = document.getElementById("surahCardsGrid");
        const juzGrid = document.getElementById("juzCardsGrid");
        const pagesGrid = document.getElementById("pagesCardsGrid");
        const filterChips = document.getElementById("surahFilterChips");
        const exploreBtn = document.getElementById("exploreAllContainer");

        if (tab === "surahs") {
            document.getElementById("tabSurahsBtn").classList.add("active");
            surahGrid.classList.remove("d-none");
            juzGrid.classList.add("d-none");
            pagesGrid.classList.add("d-none");
            if (filterChips) filterChips.style.display = "flex";
            renderSurahsGrid();
        } else if (tab === "juz") {
            document.getElementById("tabJuzBtn").classList.add("active");
            surahGrid.classList.add("d-none");
            juzGrid.classList.remove("d-none");
            pagesGrid.classList.add("d-none");
            if (filterChips) filterChips.style.display = "none";
            if (exploreBtn) exploreBtn.style.display = "none";
            renderJuzGrid();
        } else if (tab === "pages") {
            document.getElementById("tabPagesBtn").classList.add("active");
            surahGrid.classList.add("d-none");
            juzGrid.classList.add("d-none");
            pagesGrid.classList.remove("d-none");
            if (filterChips) filterChips.style.display = "none";
            if (exploreBtn) exploreBtn.style.display = "none";
            renderPagesGrid();
        }
    }

    function renderJuzGrid() {
        const container = document.getElementById("juzCardsGrid");
        if (!container || !window.QURAN_DATA) return;

        const lang = window.currentQuranLang || 'bn';
        const isBn = lang === 'bn';
        const baseUrl = window.APP_BASE_URL || '';
        let html = "";
        window.QURAN_DATA.juzList.forEach(j => {
            const jTitle = isBn ? j.banglaName : j.name;
            const jSub = isBn ? j.name : `Part ${j.id}`;
            const badge = isBn ? `পারা ${safeToBn(j.id)}` : `Juz ${j.id}`;

            html += `
                <a href="${baseUrl}/juz/${j.id}" class="surah-card">
                    <div class="surah-card-left">
                        <div class="surah-num-box">${j.id}</div>
                        <div>
                            <h4 class="surah-name-en ${isBn ? 'font-bangla' : ''} mb-0">${jTitle}</h4>
                            <p class="surah-meaning mb-0">${jSub}</p>
                        </div>
                    </div>
                    <div class="surah-card-right">
                        <div class="surah-arabic-title">${j.arabicName}</div>
                        <span class="badge-origin-pill makki">${badge}</span>
                    </div>
                </a>
            `;
        });

        container.innerHTML = html;
    }

    function renderPagesGrid() {
        const container = document.getElementById("pagesCardsGrid");
        if (!container) return;

        const lang = window.currentQuranLang || 'bn';
        const isBn = lang === 'bn';
        const baseUrl = window.APP_BASE_URL || '';
        let html = "";
        for (let p = 1; p <= 604; p += 20) {
            const pTitle = isBn ? `পৃষ্ঠা ${safeToBn(p)}` : `Page ${p}`;
            const badge = isBn ? '<i class="fa-solid fa-book-open me-1"></i> মুসহাফ' : '<i class="fa-solid fa-book-open me-1"></i> Mushaf';

            html += `
                <a href="${baseUrl}/surah/1" class="surah-card">
                    <div class="surah-card-left">
                        <div class="surah-num-box">${p}</div>
                        <div>
                            <h4 class="surah-name-en ${isBn ? 'font-bangla' : ''} mb-0">${pTitle}</h4>
                            <p class="surah-meaning mb-0">Page ${p} of 604</p>
                        </div>
                    </div>
                    <div class="surah-card-right">
                        <span class="badge-origin-pill madani">${badge}</span>
                    </div>
                </a>
            `;
        }

        container.innerHTML = html;
    }

    // 5. Update Last Played Widget
    function updateLastPlayedWidget() {
        const lastSurah = parseInt(localStorage.getItem("quran_last_surah") || "18");
        const lastAyah = parseInt(localStorage.getItem("quran_last_ayah") || "1");
        const lang = window.currentQuranLang || 'bn';
        const isBn = lang === 'bn';

        if (window.QURAN_DATA) {
            const s = window.QURAN_DATA.surahs.find(item => item.id === lastSurah) || window.QURAN_DATA.surahs[17];
            const arEl = document.getElementById("lastPlayedArabic");
            const nameEl = document.getElementById("lastPlayedName");
            const verseEl = document.getElementById("lastPlayedVerse");
            const badgeEl = document.getElementById("lastPlayedBadgeText");
            const playBtn = document.querySelector(".last-played-playbtn");

            if (arEl) arEl.textContent = s.arabic;
            if (nameEl) {
                nameEl.textContent = isBn ? `সূরা ${s.bangla}` : `Surah ${s.name}`;
                if (isBn) nameEl.classList.add("font-bangla");
                else nameEl.classList.remove("font-bangla");
            }
            if (verseEl) verseEl.textContent = isBn ? `আয়াত ${safeToBn(lastAyah)} · চালিয়ে যান` : `Ayah ${lastAyah} · Continue`;
            if (badgeEl) badgeEl.textContent = isBn ? "সর্বশেষ শোনা হয়েছে" : "Last Listened";
            if (playBtn) playBtn.title = isBn ? "চালান" : "Play";
        }
    }

    function playLastPlayed() {
        const lastSurah = parseInt(localStorage.getItem("quran_last_surah") || "18");
        const lastAyah = parseInt(localStorage.getItem("quran_last_ayah") || "1");
        const baseUrl = window.APP_BASE_URL || '';
        window.location.href = `${baseUrl}/surah/${lastSurah}?ayah=${lastAyah}&autoplay=1`;
    }
</script>
@endsection

