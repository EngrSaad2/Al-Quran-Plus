@extends('layouts.public')

@section('title', 'Surah ' . $surahId . ' — Quran Mazid Reader')

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
        <a href="{{ route('public.home') }}" class="mini-nav-btn" title="হোম">
            <i class="fa-solid fa-house"></i>
        </a>
        <button class="mini-nav-btn active" id="toggleNavDrawerBtn" onclick="toggleNavPanel()" title="সূরা তালিকা">
            <i class="fa-solid fa-book-quran"></i>
        </button>
        <a href="{{ route('public.bookmarks') }}" class="mini-nav-btn" title="বুকমার্ক">
            <i class="fa-solid fa-bookmark"></i>
        </a>
        <a href="{{ route('public.search') }}" class="mini-nav-btn" title="অনুসন্ধান">
            <i class="fa-solid fa-magnifying-glass"></i>
        </a>
        <button class="mini-nav-btn mt-auto" onclick="toggleSettingsPanel()" title="রিডিং সেটিংস">
            <i class="fa-solid fa-gear"></i>
        </button>
    </aside>

    <!-- Left Surah Navigation Panel (Collapsible / Responsive) -->
    <aside class="reader-nav-panel" id="readerNavPanel">
        <div class="reader-nav-header">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h5 class="fw-bold font-bangla mb-0">কুরআন নেভিগেশন</h5>
                    <p class="text-muted small mb-0">সূরা নির্বাচন করুন</p>
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
                <button class="btn-icon-circle d-md-none" onclick="toggleNavPanel()" title="সূরা তালিকা">
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
                <a href="{{ route('public.home') }}" class="btn-icon-circle" title="হোমে ফিরুন">
                    <i class="fa-solid fa-house"></i>
                </a>
                <button class="btn-icon-circle" id="readerThemeToggle" onclick="document.getElementById('themeToggleBtn').click()" title="থিম পরিবর্তন">
                    <i class="fa-solid fa-moon"></i>
                </button>
                <button class="btn-icon-circle" onclick="toggleSettingsPanel()" title="রিডিং সেটিংস">
                    <i class="fa-solid fa-sliders"></i>
                </button>
            </div>
        </header>

        <!-- Verses Scrollable Area -->
        <main class="reader-scroll-container">
            <!-- Surah Header Banner -->
            <div class="surah-header-banner" id="surahBanner">
                <div class="surah-banner-arabic" id="bannerArabicName">...</div>
                <h2 class="surah-banner-english font-bangla" id="bannerBanglaName">...</h2>
                <div class="surah-banner-meta" id="bannerMetaInfo">
                    <span><i class="fa-solid fa-kaaba text-warning me-1"></i> মাক্কী</span>
                    <span>•</span>
                    <span>৭ আয়াত</span>
                    <span>•</span>
                    <span>পারা ১</span>
                </div>
                @if($surahId != 9)
                    <div class="surah-bismillah-box">بِسْمِ ٱللَّٰهِ ٱلرَّحْمَٰنِ ٱلرَّحِيمِ</div>
                @endif
            </div>

            <!-- Ayahs Container -->
            <div id="ayahsContainer">
                <!-- Dynamically rendered by quran-reader.js -->
            </div>
        </main>
    </div>

    <!-- Right Reading Settings Panel -->
    <aside class="reader-settings-panel" id="readerSettingsPanel">
        <div class="settings-panel-header">
            <h6 class="fw-bold font-bangla mb-0"><i class="fa-solid fa-gear text-primary me-2"></i> রিডিং সেটিংস</h6>
            <button class="btn-icon-circle" onclick="toggleSettingsPanel()">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="settings-panel-content">
            <!-- Qari / Reciter Selection -->
            <div class="setting-group-item">
                <div class="setting-label">ক্বারী / তিলাওয়াতকারী</div>
                <select class="custom-select-box" id="settingReciterSelect" onchange="onReciterChanged(this)">
                    <!-- Populated dynamically -->
                </select>
            </div>

            <!-- Arabic Font Family -->
            <div class="setting-group-item">
                <div class="setting-label">আরবি ফন্ট স্টাইল</div>
                <select class="custom-select-box" id="settingArabicFont">
                    <option value="font-amiri">Amiri (উসমানী স্টাইল)</option>
                    <option value="font-scheherazade">Scheherazade New</option>
                    <option value="font-naskh">Noto Naskh Arabic</option>
                </select>
            </div>

            <!-- Arabic Font Size Slider -->
            <div class="setting-group-item">
                <div class="setting-label">
                    <span>আরবি ফন্ট সাইজ</span>
                    <span class="badge bg-secondary text-primary" id="arabicSizeValue">28px</span>
                </div>
                <input type="range" class="custom-range-slider" id="settingArabicSize" min="20" max="48" value="28">
            </div>

            <!-- Translation Font Size Slider -->
            <div class="setting-group-item">
                <div class="setting-label">
                    <span>অনুবাদ ফন্ট সাইজ</span>
                    <span class="badge bg-secondary text-primary" id="transSizeValue">15px</span>
                </div>
                <input type="range" class="custom-range-slider" id="settingTransSize" min="12" max="24" value="15">
            </div>

            <hr style="border-color: var(--border-color); margin: 20px 0;">

            <!-- Translations Toggles -->
            <div class="setting-group-item">
                <div class="setting-label">অনুবাদ প্রদর্শন</div>
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" id="settingShowBangla" checked>
                    <label class="form-check-label small font-bangla" for="settingShowBangla">বাংলা অনুবাদ</label>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="settingShowEnglish" checked>
                    <label class="form-check-label small" for="settingShowEnglish">English Translation</label>
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

    function initReaderView() {
        if (!window.QURAN_DATA) return;

        const s = window.QURAN_DATA.surahs.find(item => item.id === currentSurahId) || window.QURAN_DATA.surahs[0];
        
        // Update banner
        const arName = document.getElementById("bannerArabicName");
        const bnName = document.getElementById("bannerBanglaName");
        const headerTitle = document.getElementById("headerSurahTitle");
        const metaInfo = document.getElementById("bannerMetaInfo");

        if (arName) arName.textContent = s.arabic;
        if (bnName) bnName.textContent = `${s.bangla} (${s.name})`;
        if (headerTitle) headerTitle.textContent = `${s.bangla} (${s.name})`;
        if (metaInfo) {
            const icon = s.type === "Makki" ? "🕋 মাক্কী" : "🕌 মাদানী";
            metaInfo.innerHTML = `
                <span>${icon}</span>
                <span>•</span>
                <span>${s.verses} আয়াত</span>
                <span>•</span>
                <span>পারা ${s.juz.join(', ')}</span>
            `;
        }

        // Render Nav Surah List
        renderNavSurahList();

        // Populate Reciters in Settings
        populateRecitersDropdown();

        // Initialize QuranReader instance
        window.quranReader = new QuranReader(currentSurahId);
    }

    function renderNavSurahList(filterQuery = "") {
        const container = document.getElementById("navSurahList");
        if (!container || !window.QURAN_DATA) return;

        let list = window.QURAN_DATA.surahs;
        if (filterQuery) {
            const q = filterQuery.toLowerCase();
            list = list.filter(s => 
                s.name.toLowerCase().includes(q) || 
                s.bangla.toLowerCase().includes(q) || 
                s.arabic.includes(q) || 
                String(s.id).includes(q)
            );
        }

        const baseUrl = window.APP_BASE_URL || '';
        let html = "";
        list.forEach(s => {
            const isActive = s.id === currentSurahId;
            const originClass = s.type === "Makki" ? "text-warning" : "text-emerald";

            html += `
                <a href="${baseUrl}/surah/${s.id}" class="surah-nav-item ${isActive ? 'active' : ''}">
                    <div class="surah-num-box" style="width:32px; height:32px; font-size:0.75rem;">${s.id}</div>
                    <div style="flex:1; min-width:0;">
                        <div class="d-flex align-items-center justify-content-between">
                            <h6 class="mb-0 font-bangla fw-bold text-truncate small ${isActive ? 'text-primary' : ''}">${s.bangla}</h6>
                            <span class="font-amiri fw-bold" style="font-size:1.1rem;">${s.arabic}</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between text-muted" style="font-size:0.68rem;">
                            <span>${s.name}</span>
                            <span class="${originClass}">${s.type === 'Makki' ? 'মাক্কী' : 'মাদানী'}</span>
                        </div>
                    </div>
                </a>
            `;
        });

        container.innerHTML = html;

        // Auto scroll to active surah in sidebar
        const activeEl = container.querySelector(".surah-nav-item.active");
        if (activeEl) {
            activeEl.scrollIntoView({ block: "nearest" });
        }
    }

    function filterNavSurahs(query) {
        renderNavSurahList(query);
    }

    function populateRecitersDropdown() {
        const select = document.getElementById("settingReciterSelect");
        if (!select || !window.QURAN_DATA) return;

        const currentReciter = localStorage.getItem("quran_reciter") || "Alafasy_128kbps";
        let html = "";

        window.QURAN_DATA.reciters.forEach(r => {
            const isSelected = r.subfolder === currentReciter ? "selected" : "";
            html += `<option value="${r.subfolder}" data-name="${r.name}" ${isSelected}>${r.name} (${r.arabicName})</option>`;
        });

        select.innerHTML = html;
    }

    function onReciterChanged(select) {
        const selectedOption = select.options[select.selectedIndex];
        const subfolder = select.value;
        const name = selectedOption.getAttribute("data-name");

        if (window.quranPlayer) {
            window.quranPlayer.setReciter(subfolder, name);
        }
    }

    function toggleNavPanel() {
        const panel = document.getElementById("readerNavPanel");
        if (panel) panel.classList.toggle("open");
    }

    function toggleSettingsPanel() {
        const panel = document.getElementById("readerSettingsPanel");
        if (panel) panel.classList.toggle("open");
    }
</script>
@endsection
