@extends('layouts.public')

@section('meta')
    @include('partials.seo-meta', [
        'metaTitle' => "সূরা {$surah['bangla']} (Surah {$surah['name']}) - বাংলা অর্থ, আরবি ও অডিও | Al Quran Plus",
        'metaDesc' => "পবিত্র কুরআনের {$surah['id']} নম্বর সূরা {$surah['bangla']} (Surah {$surah['name']})। মোট {$surah['verses']} আয়াত, " . ($surah['type'] == 'Makki' ? 'মাক্কী' : 'মাদানী') . " সূরা। বিশুদ্ধ আরবি তিলাওয়াত, সহজ বাংলা ও ইংরেজি অনুবাদসহ অনলাইনে পড়ুন।",
        'metaKeywords' => "সূরা {$surah['bangla']}, Surah {$surah['name']}, সূরা {$surah['bangla']} বাংলা অর্থ, Surah {$surah['name']} Bangla Translation, {$surah['arabic']}, পবিত্র কুরআন সূরা {$surah['id']}",
        'canonicalUrl' => url('/surah/' . $surahId),
        'ogType' => 'article',
        'breadcrumbSchema' => $breadcrumbSchema ?? null,
        'surahSchema' => $surahSchema ?? null
    ])
@endsection

@section('styles')
<style>
    /* Full height fixed layout for reader */
    body {
        padding-bottom: 78px;
        overflow: hidden;
    }
    .navbar-floating-container {
        display: none !important;
    }
</style>
@endsection

@section('content')

<div class="reader-page-layout">
    <!-- Left Mini Icon Sidebar -->
    <aside class="reader-mini-sidebar d-none d-md-flex">
        <a href="{{ route('public.home') }}" class="mini-nav-btn" id="miniNavHome" title="হোম">
            <i class="fa-solid fa-house"></i>
        </a>
        <button class="mini-nav-btn active" id="toggleNavDrawerBtn" onclick="toggleNavPanel()" title="সূরা তালিকা">
            <i class="fa-solid fa-book-quran"></i>
        </button>
        <a href="{{ route('public.bookmarks') }}" class="mini-nav-btn" id="miniNavBookmarks" title="বুকমার্ক">
            <i class="fa-solid fa-bookmark"></i>
        </a>
        <a href="{{ route('public.search') }}" class="mini-nav-btn" id="miniNavSearch" title="অনুসন্ধান">
            <i class="fa-solid fa-magnifying-glass"></i>
        </a>
        <button class="mini-nav-btn mt-auto" id="miniNavSettings" onclick="toggleSettingsPanel()" title="রিডিং সেটিংস">
            <i class="fa-solid fa-gear"></i>
        </button>
    </aside>

    <!-- Left Surah Navigation Panel (Collapsible / Responsive) -->
    <aside class="reader-nav-panel" id="readerNavPanel">
        <div class="reader-nav-header">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h5 class="fw-bold font-bangla mb-0" id="navPanelTitle">কুরআন নেভিগেশন</h5>
                    <p class="text-muted small mb-0" id="navPanelSub">সূরা নির্বাচন করুন</p>
                </div>
                <button class="btn-icon-circle d-md-none" onclick="toggleNavPanel()">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="reader-search-box position-relative">
                <i class="fa-solid fa-magnifying-glass position-absolute" style="left:12px; top:11px; color:var(--text-dim); font-size:0.85rem;"></i>
                <input type="text" id="navSurahSearch" class="reader-search-input" placeholder="সূরা খুঁজুন..." oninput="filterNavSurahs(this.value)">
            </div>
        </div>

        <div class="reader-surah-list" id="navSurahList">
            <!-- Populated dynamically with 114 surahs -->
        </div>
    </aside>

    <!-- Main Reader Content Area -->
    <div class="reader-main-area">
        <!-- Top Reader Header Bar -->
        <header class="reader-top-header">
            <div class="d-flex align-items-center gap-3">
                <button class="btn-icon-circle d-md-none" id="readerMobileNavBtn" onclick="toggleNavPanel()" title="সূরা তালিকা">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="reader-surah-badge">
                    <button class="btn-icon-circle" id="headerQuickPlayBtn" style="width:28px; height:28px; font-size:0.75rem;" onclick="window.quranPlayer.playSurah({{ $surahId }}, 1)" title="সূরা শুনুন">
                        <i class="fa-solid fa-play"></i>
                    </button>
                    <span class="font-bangla fw-bold small" id="headerSurahTitle">সূরা লোড হচ্ছে...</span>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <!-- Change Qari Option on Header Circle with Qari Picture -->
                <div class="d-inline-block" id="headerQariDropdownContainer">
                    <button class="btn-qari-header-circle" id="headerQariBtn" type="button" data-bs-toggle="modal" data-bs-target="#qariSelectModal" onclick="openQariModal()" title="ক্বারী পরিবর্তন করুন / Change Reciter">
                        <img id="headerQariImg" src="{{ asset('images/reciters/alafasy.webp') }}" alt="Qari" class="qari-header-avatar">
                    </button>
                </div>

                <a href="{{ route('public.home') }}" class="btn-icon-circle" id="readerHeaderHome" title="হোমে ফিরুন">
                    <i class="fa-solid fa-house"></i>
                </a>
                <button class="btn-icon-circle" id="readerThemeToggle" onclick="document.getElementById('themeToggleBtn').click()" title="থিম পরিবর্তন">
                    <i class="fa-solid fa-moon"></i>
                </button>
                <button class="btn-icon-circle" id="readerHeaderSettingsBtn" onclick="toggleSettingsPanel()" title="রিডিং সেটিংস">
                    <i class="fa-solid fa-sliders"></i>
                </button>
            </div>
        </header>

        <!-- Verses Scrollable Area -->
        <main class="reader-scroll-container">
            <!-- Surah Header Banner (Server Rendered for Googlebot) -->
            <div class="surah-header-banner" id="surahBanner">
                <div class="surah-banner-arabic" id="bannerArabicName">{{ $surah['arabic'] }}</div>
                <h1 class="surah-banner-english font-bangla" id="bannerBanglaName">সূরা {{ $surah['bangla'] }} (Surah {{ $surah['name'] }})</h1>
                <div class="surah-banner-meta" id="bannerMetaInfo">
                    <span><i class="fa-solid {{ $surah['type'] == 'Makki' ? 'fa-kaaba text-warning' : 'fa-mosque text-emerald' }} me-1"></i> {{ $surah['type'] == 'Makki' ? 'মাক্কী' : 'মাদানী' }}</span>
                    <span>•</span>
                    <span>{{ App\Services\QuranDataService::toBanglaNumber($surah['verses']) }} আয়াত ({{ $surah['verses'] }} Verses)</span>
                    <span>•</span>
                    <span>পারা {{ App\Services\QuranDataService::toBanglaNumber(implode(', ', $surah['juz'])) }}</span>
                </div>
                @if($surahId != 9)
                    <div class="surah-bismillah-box">بِسْمِ ٱللَّٰهِ ٱلرَّحْمَٰنِ ٱلرَّحِيمِ</div>
                @endif
            </div>

            <!-- Crawlable Noscript Summary for Search Crawlers -->
            <noscript>
                <div style="background: var(--bg-card); padding: 25px; border-radius: 16px; margin: 20px 0; border: 1px solid var(--border-color); color: var(--text-primary);">
                    <h2 class="h5 font-bangla fw-bold">সূরা {{ $surah['bangla'] }} ({{ $surah['name'] }}) - পরিচিতি ও অর্থ</h2>
                    <p class="small text-muted mb-2">অর্থ: {{ $surah['banglaMeaning'] }} ({{ $surah['englishMeaning'] }}) | অবতীর্ণ: {{ $surah['type'] == 'Makki' ? 'মক্কা মুকাররমা' : 'মদিনা মুনাওয়ারা' }} | পারা: {{ implode(', ', $surah['juz']) }}</p>
                    <p class="small mb-0">পবিত্র কুরআনের {{ $surah['id'] }} নম্বর সূরা {{ $surah['bangla'] }}। এতে মোট {{ $surah['verses'] }}টি আয়াত রয়েছে। নিচে জাভাস্ক্রিপ্ট সক্রিয় করে সম্পূর্ণ সূরা বিশুদ্ধ আরবি পাঠ, সহজ বাংলা অনুবাদ, ইংরেজি অর্থ ও অডিও তিলাওয়াত শুনুন।</p>
                </div>
            </noscript>

            <!-- Ayahs Container -->
            <div id="ayahsContainer">
                <!-- Dynamically rendered by quran-reader.js -->
            </div>
        </main>
    </div>

    <!-- Right Reading Settings Panel -->
    <aside class="reader-settings-panel" id="readerSettingsPanel">
        <div class="settings-panel-header">
            <h6 class="fw-bold font-bangla mb-0" id="settingsHeaderTitle"><i class="fa-solid fa-gear text-primary me-2"></i> রিডিং সেটিংস</h6>
            <button class="btn-icon-circle" onclick="closeSettingsPanel()" title="বন্ধ করুন">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="settings-panel-content">
            <!-- Qari / Reciter Selection -->
            <div class="setting-group-item">
                <div class="setting-label" id="labelReciter">ক্বারী / তিলাওয়াতকারী</div>
                <button type="button" class="qari-setting-preview-btn" data-bs-toggle="modal" data-bs-target="#qariSelectModal" onclick="openQariModal()" id="settingQariBtn" title="ক্বারী পরিবর্তন করুন / Change Reciter">
                    <img id="settingQariImg" src="{{ asset('images/reciters/alafasy.webp') }}" alt="Qari" class="qari-setting-thumb">
                    <div class="qari-setting-info">
                        <div class="qari-setting-name font-bangla" id="settingQariName">মিশারি রশিদ আল-আফাসি</div>
                        <div class="qari-setting-sub" id="settingQariSub">مشاري راشد العفاسي</div>
                    </div>
                    <span class="qari-setting-action-badge font-bangla">
                        <span id="labelChangeQari">পরিবর্তন</span>
                        <i class="fa-solid fa-chevron-right ms-1"></i>
                    </span>
                </button>
            </div>

            <!-- Arabic Font Family -->
            <div class="setting-group-item">
                <div class="setting-label" id="labelArabicFont">আরবি ফন্ট স্টাইল</div>
                <select class="custom-select-box" id="settingArabicFont">
                    <option value="font-amiri">Amiri (উসমানী স্টাইল)</option>
                    <option value="font-scheherazade">Scheherazade New</option>
                    <option value="font-naskh">Noto Naskh Arabic</option>
                </select>
            </div>

            <!-- Arabic Font Size Slider -->
            <div class="setting-group-item">
                <div class="setting-label">
                    <span id="labelArabicSize">আরবি ফন্ট সাইজ</span>
                    <span class="badge badge-font-size" id="arabicSizeValue">25px</span>
                </div>
                <input type="range" class="custom-range-slider" id="settingArabicSize" min="18" max="44" value="25">
            </div>

            <!-- Translation Font Size Slider -->
            <div class="setting-group-item">
                <div class="setting-label">
                    <span id="labelTransSize">অনুবাদ ফন্ট সাইজ</span>
                    <span class="badge badge-font-size" id="transSizeValue">15px</span>
                </div>
                <input type="range" class="custom-range-slider" id="settingTransSize" min="12" max="24" value="15">
            </div>

            <hr style="border-color: var(--border-color); margin: 20px 0;">

            <!-- Translations Toggles -->
            <div class="setting-group-item">
                <div class="setting-label" id="labelTransDisplay">অনুবাদ প্রদর্শন</div>
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" id="settingShowBangla" checked>
                    <label class="form-check-label small font-bangla" for="settingShowBangla" id="labelShowBn">বাংলা অনুবাদ</label>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="settingShowEnglish">
                    <label class="form-check-label small" for="settingShowEnglish" id="labelShowEn">English Translation</label>
                </div>
            </div>
        </div>
    </aside>
</div>

@endsection

@section('scripts')
<script>
    const currentSurahId = {{ $surahId }};

    document.addEventListener("DOMContentLoaded", () => {
        initReaderView();
    });

    // Listen to global language changes
    window.addEventListener("quranLanguageChanged", () => {
        updateReaderLanguageUI();
    });

    function initReaderView() {
        updateReaderLanguageUI();

        // Check if autoplay requested and target ayah specified
        const urlParams = new URLSearchParams(window.location.search);
        const shouldAutoplay = urlParams.get('autoplay') === '1' || urlParams.get('play') === '1';
        const targetAyah = parseInt(urlParams.get('ayah')) || null;

        // Update Reciter Previews in Settings & Header
        if (window.updateAllQariUIPreviews) {
            window.updateAllQariUIPreviews();
        }

        // Initialize QuranReader instance with targetAyah
        window.quranReader = new QuranReader(currentSurahId, targetAyah);

        // Sync global audio player to current surah and target ayah
        if (window.quranPlayer) {
            window.quranPlayer.loadSurah(currentSurahId, shouldAutoplay, targetAyah || 1);
        }
    }

    function updateReaderLanguageUI() {
        if (!window.QURAN_DATA) return;
        const lang = window.currentQuranLang || localStorage.getItem("quran_lang") || "bn";
        const isBn = lang === "bn";

        const s = window.QURAN_DATA.surahs.find(item => item.id === currentSurahId) || window.QURAN_DATA.surahs[0];
        
        // Update banner
        const arName = document.getElementById("bannerArabicName");
        const bnName = document.getElementById("bannerBanglaName");
        const headerTitle = document.getElementById("headerSurahTitle");
        const metaInfo = document.getElementById("bannerMetaInfo");

        if (arName) arName.textContent = s.arabic;
        if (bnName) {
            bnName.textContent = isBn ? s.bangla : `${s.name} (${s.englishMeaning})`;
            if (isBn) bnName.classList.add("font-bangla");
            else bnName.classList.remove("font-bangla");
        }
        if (headerTitle) {
            headerTitle.innerHTML = `<span class="header-surah-name">${isBn ? s.bangla : s.name}</span><span class="header-surah-meaning d-none d-md-inline text-muted fw-normal ms-1">(${isBn ? s.banglaMeaning : s.englishMeaning})</span>`;
            if (isBn) headerTitle.classList.add("font-bangla");
            else headerTitle.classList.remove("font-bangla");
        }
        if (metaInfo) {
            const icon = isBn ? (s.type === "Makki" ? "🕋 মাক্কী" : "🕌 মাদানী") : `🕋 ${s.type}`;
            const versesText = isBn ? `${window.toBanglaNumber(s.verses)} আয়াত` : `${s.verses} Verses`;
            const juzText = isBn ? `পারা ${window.toBanglaNumber(s.juz.join(', '))}` : `Juz ${s.juz.join(', ')}`;
            metaInfo.innerHTML = `
                <span>${icon}</span>
                <span>•</span>
                <span>${versesText}</span>
                <span>•</span>
                <span>${juzText}</span>
            `;
        }

        // Mini sidebar tooltips
        const miniHome = document.getElementById("miniNavHome");
        const miniNav = document.getElementById("toggleNavDrawerBtn");
        const miniBook = document.getElementById("miniNavBookmarks");
        const miniSrch = document.getElementById("miniNavSearch");
        const miniSet = document.getElementById("miniNavSettings");
        if (miniHome) miniHome.title = isBn ? "হোম" : "Home";
        if (miniNav) miniNav.title = isBn ? "সূরা তালিকা" : "Surah List";
        if (miniBook) miniBook.title = isBn ? "বুকমার্ক" : "Bookmarks";
        if (miniSrch) miniSrch.title = isBn ? "অনুসন্ধান" : "Search";
        if (miniSet) miniSet.title = isBn ? "রিডিং সেটিংস" : "Reading Settings";

        // Top header buttons
        const mNavBtn = document.getElementById("readerMobileNavBtn");
        const quickPlay = document.getElementById("headerQuickPlayBtn");
        const headHome = document.getElementById("readerHeaderHome");
        const themeBtn = document.getElementById("readerThemeToggle");
        const headSet = document.getElementById("readerHeaderSettingsBtn");
        if (mNavBtn) mNavBtn.title = isBn ? "সূরা তালিকা" : "Surah List";
        if (quickPlay) quickPlay.title = isBn ? "সূরা শুনুন" : "Play Surah";
        if (headHome) headHome.title = isBn ? "হোমে ফিরুন" : "Back to Home";
        if (themeBtn) themeBtn.title = isBn ? "থিম পরিবর্তন" : "Toggle Theme";
        if (headSet) headSet.title = isBn ? "রিডিং সেটিংস" : "Reading Settings";

        // Drawer labels
        const navTitle = document.getElementById("navPanelTitle");
        const navSub = document.getElementById("navPanelSub");
        const navSearch = document.getElementById("navSurahSearch");
        if (navTitle) navTitle.textContent = isBn ? "কুরআন নেভিগেশন" : "Quran Navigation";
        if (navSub) navSub.textContent = isBn ? "সূরা নির্বাচন করুন" : "Select Surah";
        if (navSearch) navSearch.placeholder = isBn ? "সূরা খুঁজুন..." : "Search Surah...";

        // Settings panel labels & options
        const sTitle = document.getElementById("settingsHeaderTitle");
        const lReciter = document.getElementById("labelReciter");
        const lArabicFont = document.getElementById("labelArabicFont");
        const lArabicSize = document.getElementById("labelArabicSize");
        const lTransSize = document.getElementById("labelTransSize");
        const lTransDisplay = document.getElementById("labelTransDisplay");
        const lShowBn = document.getElementById("labelShowBn");
        const lShowEn = document.getElementById("labelShowEn");
        if (sTitle) sTitle.innerHTML = `<i class="fa-solid fa-gear text-primary me-2"></i> ${isBn ? "রিডিং সেটিংস" : "Reading Settings"}`;
        if (lReciter) lReciter.textContent = isBn ? "ক্বারী / তিলাওয়াতকারী" : "Reciter / Qari";
        if (lArabicFont) lArabicFont.textContent = isBn ? "আরবি ফন্ট স্টাইল" : "Arabic Font Style";
        if (lArabicSize) lArabicSize.textContent = isBn ? "আরবি ফন্ট সাইজ" : "Arabic Font Size";
        if (lTransSize) lTransSize.textContent = isBn ? "অনুবাদ ফন্ট সাইজ" : "Translation Font Size";
        if (lTransDisplay) lTransDisplay.textContent = isBn ? "অনুবাদ প্রদর্শন" : "Display Translations";
        if (lShowBn) lShowBn.textContent = isBn ? "বাংলা অনুবাদ" : "Bengali Translation";
        if (lShowEn) lShowEn.textContent = isBn ? "English Translation" : "English Translation (Sahih)";

        const amiriOpt = document.querySelector('#settingArabicFont option[value="font-amiri"]');
        if (amiriOpt) amiriOpt.textContent = isBn ? "Amiri (উসমানী স্টাইল)" : "Amiri (Uthmani Style)";

        // Re-render sidebar list
        renderNavSurahList();
    }

    function renderNavSurahList(filterQuery = "") {
        const container = document.getElementById("navSurahList");
        if (!container || !window.QURAN_DATA) return;

        const lang = window.currentQuranLang || localStorage.getItem("quran_lang") || "bn";
        const isBn = lang === "bn";

        let list = window.QURAN_DATA.surahs;
        if (filterQuery) {
            const q = filterQuery.toLowerCase();
            const normalizeStr = (str) => (str || '').toLowerCase().replace(/[-_']/g, '').replace(/aa/g, 'a').replace(/ee/g, 'i').replace(/oo/g, 'u');
            const normQ = normalizeStr(q);
            list = list.filter(s => 
                s.name.toLowerCase().includes(q) || 
                normalizeStr(s.name).includes(normQ) || 
                s.bangla.toLowerCase().includes(q) || 
                s.englishMeaning.toLowerCase().includes(q) || 
                normalizeStr(s.englishMeaning).includes(normQ) || 
                s.arabic.includes(q) || 
                String(s.id).includes(q)
            );
        }

        const baseUrl = window.APP_BASE_URL || '';
        let html = "";
        list.forEach(s => {
            const isActive = s.id === currentSurahId;
            const originClass = s.type === "Makki" ? "text-warning" : "text-emerald";
            const originBadge = isBn ? (s.type === 'Makki' ? 'মাক্কী' : 'মাদানী') : s.type;
            const title = isBn ? s.bangla : s.name;
            const sub = isBn ? (s.banglaMeaning || s.bangla) : s.englishMeaning;

            html += `
                <a href="${baseUrl}/surah/${s.id}" class="surah-nav-item ${isActive ? 'active selected-surah' : ''}" id="navSurahItem-${s.id}">
                    <div class="surah-num-box ${isActive ? 'active-num' : ''}" style="width:32px; height:32px; font-size:0.75rem;">${s.id}</div>
                    <div style="flex:1; min-width:0;">
                        <div class="d-flex align-items-center justify-content-between">
                            <h6 class="mb-0 ${isBn ? 'font-bangla' : ''} fw-bold text-truncate small ${isActive ? 'text-primary' : ''}">${title}</h6>
                            <span class="font-amiri fw-bold" style="font-size:1.1rem;">${s.arabic}</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between surah-nav-sub-row" style="font-size:0.72rem; margin-top:2px;">
                            <span class="surah-nav-sub">${sub}</span>
                            <span class="${originClass} fw-bold" style="font-size:0.68rem;">${originBadge}</span>
                        </div>
                    </div>
                </a>
            `;
        });

        container.innerHTML = html;

        // Auto scroll active surah smoothly to center of sidebar
        function scrollActiveSurahToCenter() {
            const activeEl = container.querySelector(".surah-nav-item.active");
            if (activeEl) {
                const targetScroll = activeEl.offsetTop - (container.clientHeight / 2) + (activeEl.clientHeight / 2);
                container.scrollTo({ top: Math.max(0, targetScroll), behavior: "smooth" });
            }
        }
        scrollActiveSurahToCenter();
        setTimeout(scrollActiveSurahToCenter, 100);
        setTimeout(scrollActiveSurahToCenter, 300);
    }

    function filterNavSurahs(query) {
        renderNavSurahList(query);
    }

    function selectQari(subfolder, name) {
        if (window.onSelectQariCard) {
            window.onSelectQariCard(subfolder, name);
        } else if (window.quranPlayer) {
            window.quranPlayer.setReciter(subfolder, name);
            if (window.updateAllQariUIPreviews) window.updateAllQariUIPreviews();
        }
    }

    function toggleSettingsPanel() {
        const panel = document.getElementById("readerSettingsPanel");
        if (!panel) return;
        const isMobile = window.innerWidth <= 1200;
        if (isMobile) {
            panel.classList.toggle("open");
        } else {
            panel.classList.toggle("closed");
        }
        updateSettingsBtnState();
    }

    function closeSettingsPanel() {
        const panel = document.getElementById("readerSettingsPanel");
        if (!panel) return;
        panel.classList.remove("open");
        panel.classList.add("closed");
        updateSettingsBtnState();
    }

    function openSettingsPanel() {
        const panel = document.getElementById("readerSettingsPanel");
        if (!panel) return;
        panel.classList.add("open");
        panel.classList.remove("closed");
        updateSettingsBtnState();
    }

    function updateSettingsBtnState() {
        const panel = document.getElementById("readerSettingsPanel");
        const headerBtn = document.getElementById("readerHeaderSettingsBtn");
        const miniBtn = document.getElementById("miniNavSettings");
        if (!panel) return;
        const isMobile = window.innerWidth <= 1200;
        const isOpen = isMobile ? panel.classList.contains("open") : !panel.classList.contains("closed");
        if (headerBtn) {
            if (isOpen) headerBtn.classList.add("active");
            else headerBtn.classList.remove("active");
        }
        if (miniBtn) {
            if (isOpen) miniBtn.classList.add("active");
            else miniBtn.classList.remove("active");
        }
    }

    function toggleNavPanel() {
        const panel = document.getElementById("readerNavPanel");
        const miniBtn = document.getElementById("toggleNavDrawerBtn");
        if (!panel) return;
        const isMobile = window.innerWidth <= 1024;
        if (isMobile) {
            panel.classList.toggle("open");
        } else {
            panel.classList.toggle("closed");
        }
        const isOpen = isMobile ? panel.classList.contains("open") : !panel.classList.contains("closed");
        if (miniBtn) {
            if (isOpen) miniBtn.classList.add("active");
            else miniBtn.classList.remove("active");
        }
    }

    document.addEventListener("DOMContentLoaded", () => {
        updateSettingsBtnState();
    });
</script>
@endsection

