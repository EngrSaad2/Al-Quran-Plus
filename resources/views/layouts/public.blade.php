<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Quran Mazid - Complete Quran Reading & Audio Recitation')</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <meta name="description" content="Beautiful Quran reading experience with crystal-clear Arabic text, English & Bengali translations, and audio recitation by world-renowned Qaris.">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <!-- Quran Mazid Custom CSS -->
    <link href="{{ asset('css/quranmazid.css') }}" rel="stylesheet">
    
    @yield('styles')
</head>
<body>

    <!-- Watermark Calligraphy & Ambient Orbs Background -->
    <div class="quran-bg-watermark">
        <div class="quran-bg-dots"></div>
        <div class="watermark-text-center">ٱلْقُرْآنُ ٱلْكَرِيمُ</div>
        <div class="watermark-text-left">اقْرَأْ بِاسْمِ رَبِّكَ</div>
        <div class="watermark-text-right">قُرْآنٌ মَجِيدٌ</div>
        <div class="glow-orb-top"></div>
        <div class="glow-orb-right"></div>
    </div>

    <!-- Floating Top Navigation Pill -->
    <header class="navbar-floating-container">
        <nav class="navbar-floating-pill">
            <!-- Brand Logo -->
            <a href="{{ route('public.home') }}" class="brand-logo-pill">
                <div class="brand-logo-badge">ق</div>
                <span class="font-bangla" id="navBrandText">কুরআন মাজিদ</span>
            </a>

            <!-- Center Navigation Links -->
            <div class="nav-links-pill d-none d-md-flex">
                <a href="{{ route('public.home') }}" id="navHome" class="nav-pill-item {{ request()->routeIs('public.home') ? 'active' : '' }}">হোম</a>
                <a href="{{ route('public.surah', 1) }}" id="navRead" class="nav-pill-item {{ request()->routeIs('public.surah') ? 'active' : '' }}">কুরআন পড়ুন</a>
                <a href="{{ route('public.bookmarks') }}" id="navBookmarks" class="nav-pill-item {{ request()->routeIs('public.bookmarks') ? 'active' : '' }}">বুকমার্ক</a>
                <a href="{{ route('public.search') }}" id="navSearch" class="nav-pill-item {{ request()->routeIs('public.search') ? 'active' : '' }}">অনুসন্ধান</a>
            </div>

            <!-- Right Action Buttons -->
            <div class="nav-actions-pill">
                <!-- Theme Toggle Button -->
                <button class="btn-icon-circle" id="themeToggleBtn" aria-label="Toggle Theme" title="Toggle Theme">
                    <i class="fa-solid fa-moon" id="themeIcon"></i>
                </button>

                <!-- Language Toggle -->
                <button class="btn-lang-pill" id="langToggleBtn" title="Change Language">
                    <i class="fa-solid fa-globe text-muted"></i>
                    <span id="langLabel">Ar+বাং</span>
                </button>

                <!-- Mobile Menu Toggle Button -->
                <button class="btn-icon-circle d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileNavDrawer" aria-label="Menu">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>
        </nav>
    </header>

    <!-- Mobile Offcanvas Drawer -->
    <div class="offcanvas offcanvas-start bg-dark text-light" tabindex="-1" id="mobileNavDrawer" style="background: var(--bg-card) !important; border-right: 1px solid var(--border-color);">
        <div class="offcanvas-header border-bottom" style="border-color: var(--border-color) !important;">
            <div class="d-flex align-items-center gap-2">
                <div class="brand-logo-badge">ق</div>
                <h5 class="offcanvas-title font-bangla mb-0">কুরআন মাজিদ</h5>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-4">
            <div class="d-flex flex-column gap-2">
                <a href="{{ route('public.home') }}" id="drawerHome" class="nav-pill-item text-start p-3 rounded-3 {{ request()->routeIs('public.home') ? 'active' : '' }}">
                    <i class="fa-solid fa-house me-2"></i> হোম
                </a>
                <a href="{{ route('public.surah', 1) }}" id="drawerRead" class="nav-pill-item text-start p-3 rounded-3 {{ request()->routeIs('public.surah') ? 'active' : '' }}">
                    <i class="fa-solid fa-book-quran me-2"></i> কুরআন পড়ুন
                </a>
                <a href="{{ route('public.bookmarks') }}" id="drawerBookmarks" class="nav-pill-item text-start p-3 rounded-3 {{ request()->routeIs('public.bookmarks') ? 'active' : '' }}">
                    <i class="fa-solid fa-bookmark me-2"></i> সংরক্ষিত আয়াত / বুকমার্ক
                </a>
                <a href="{{ route('public.search') }}" id="drawerSearch" class="nav-pill-item text-start p-3 rounded-3 {{ request()->routeIs('public.search') ? 'active' : '' }}">
                    <i class="fa-solid fa-magnifying-glass me-2"></i> অনুসন্ধান
                </a>
                <hr style="border-color: var(--border-color);">
                <a href="{{ route('public.about') }}" id="drawerAbout" class="nav-pill-item text-start p-3 rounded-3">
                    <i class="fa-solid fa-circle-info me-2"></i> অ্যাপ সম্পর্কে
                </a>
                <a href="{{ route('public.privacy') }}" id="drawerPrivacy" class="nav-pill-item text-start p-3 rounded-3">
                    <i class="fa-solid fa-shield-halved me-2"></i> গোপনীয়তা নীতি
                </a>
                <a href="{{ route('public.contact') }}" id="drawerContact" class="nav-pill-item text-start p-3 rounded-3">
                    <i class="fa-solid fa-envelope me-2"></i> যোগাযোগ
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content Injection -->
    <main style="position: relative; z-index: 1;">
        @yield('content')
    </main>

    <!-- Global Floating Audio Player Bar -->
    <div class="global-audio-player-bar" id="globalAudioBar">
        <!-- Track & Surah Meta -->
        <div class="audio-track-info">
            <div class="audio-thumb-circle">
                <i class="fa-solid fa-book-quran"></i>
            </div>
            <div class="audio-meta-text">
                <div class="audio-surah-name" id="audioSurahName">সূরা আল-ফাতিহা (১)</div>
                <div class="audio-reciter-name" id="audioReciterName">Mishary Rashid Alafasy</div>
            </div>
        </div>

        <!-- Center Controls & Timeline -->
        <div class="audio-controls-center">
            <div class="audio-buttons-row">
                <!-- Loop Mode Toggle -->
                <button class="btn-audio-ctrl" id="audioLoopBtn" onclick="window.quranPlayer.toggleLoopMode()" title="Repeat / Loop">
                    <i class="fa-solid fa-forward-step"></i>
                </button>

                <!-- Previous Ayah -->
                <button class="btn-audio-ctrl" onclick="window.quranPlayer.prevAyah()" title="Previous Ayah">
                    <i class="fa-solid fa-backward"></i>
                </button>

                <!-- Play / Pause Main Button -->
                <button class="btn-audio-play-main" id="audioMainPlayBtn" onclick="window.quranPlayer.togglePlayPause()" title="Play / Pause">
                    <i class="fa-solid fa-play"></i>
                </button>

                <!-- Next Ayah -->
                <button class="btn-audio-ctrl" onclick="window.quranPlayer.nextAyah()" title="Next Ayah">
                    <i class="fa-solid fa-forward"></i>
                </button>
            </div>

            <!-- Progress Timeline Bar -->
            <div class="audio-progress-row">
                <span class="audio-time-label" id="audioCurTime">0:00</span>
                <input type="range" class="audio-seek-slider" id="audioSeekSlider" min="0" max="100" value="0" oninput="window.quranPlayer.seekTo(this.value / 100)">
                <span class="audio-time-label" id="audioDurTime">0:00</span>
            </div>
        </div>

        <!-- Right Options (Speed & Volume) -->
        <div class="audio-extra-options">
            <button class="audio-speed-btn" id="audioSpeedBtn" onclick="window.quranPlayer.cycleSpeed()" title="Playback Speed">1.0x</button>
            <div class="audio-vol-container">
                <i class="fa-solid fa-volume-high"></i>
                <input type="range" class="audio-vol-slider" min="0" max="1" step="0.05" value="1" oninput="window.quranPlayer.setVolume(this.value)">
            </div>
        </div>
    </div>

    <!-- Core Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/quran-data.js') }}"></script>
    <script src="{{ asset('js/quran-audio.js') }}"></script>
    <script src="{{ asset('js/quran-reader.js') }}"></script>

    <!-- Theme & Global Logic -->
    <script>
        window.APP_BASE_URL = "{{ url('/') }}";

        // Theme initialization - DEFAULT LIGHT
        const savedTheme = localStorage.getItem("quran_theme") || "light";
        document.documentElement.setAttribute("data-theme", savedTheme);
        updateThemeIcon(savedTheme);

        const themeBtn = document.getElementById("themeToggleBtn");
        if (themeBtn) {
            themeBtn.addEventListener("click", () => {
                const current = document.documentElement.getAttribute("data-theme");
                const next = current === "dark" ? "light" : "dark";
                document.documentElement.setAttribute("data-theme", next);
                localStorage.setItem("quran_theme", next);
                updateThemeIcon(next);
            });
        }

        function updateThemeIcon(theme) {
            const icon = document.getElementById("themeIcon");
            const btn = document.getElementById("themeToggleBtn");
            if (icon) {
                // In light mode, show moon icon to switch to dark mode
                // In dark mode, show sun icon to switch to light mode
                icon.className = theme === "dark" ? "fa-solid fa-sun text-warning" : "fa-solid fa-moon text-muted";
            }
            if (btn) {
                btn.title = theme === "dark" ? "Switch to Light Mode" : "Switch to Dark Mode";
            }
            const readerThemeIcon = document.querySelector("#readerThemeToggle i");
            if (readerThemeIcon) {
                readerThemeIcon.className = theme === "dark" ? "fa-solid fa-sun text-warning" : "fa-solid fa-moon";
            }
        }

        // Global Layout Language Management
        function applyLayoutLanguage(lang) {
            window.currentQuranLang = lang;
            const isBn = lang === "bn";

            // Navbar Brand
            const brandEls = document.querySelectorAll("#navBrandText, .offcanvas-title");
            brandEls.forEach(el => el.textContent = isBn ? "কুরআন মাজিদ" : "Quran Mazid");

            // Navbar items
            const navHome = document.getElementById("navHome");
            const navRead = document.getElementById("navRead");
            const navBookmarks = document.getElementById("navBookmarks");
            const navSearch = document.getElementById("navSearch");
            if (navHome) navHome.textContent = isBn ? "হোম" : "Home";
            if (navRead) navRead.textContent = isBn ? "কুরআন পড়ুন" : "Read Quran";
            if (navBookmarks) navBookmarks.textContent = isBn ? "বুকমার্ক" : "Bookmarks";
            if (navSearch) navSearch.textContent = isBn ? "অনুসন্ধান" : "Search";

            // Mobile Drawer
            const dHome = document.getElementById("drawerHome");
            const dRead = document.getElementById("drawerRead");
            const dBookmarks = document.getElementById("drawerBookmarks");
            const dSearch = document.getElementById("drawerSearch");
            const dAbout = document.getElementById("drawerAbout");
            const dPrivacy = document.getElementById("drawerPrivacy");
            const dContact = document.getElementById("drawerContact");
            if (dHome) dHome.innerHTML = `<i class="fa-solid fa-house me-2"></i> ${isBn ? "হোম" : "Home"}`;
            if (dRead) dRead.innerHTML = `<i class="fa-solid fa-book-quran me-2"></i> ${isBn ? "কুরআন পড়ুন" : "Read Quran"}`;
            if (dBookmarks) dBookmarks.innerHTML = `<i class="fa-solid fa-bookmark me-2"></i> ${isBn ? "সংরক্ষিত আয়াত / বুকমার্ক" : "Saved Bookmarks"}`;
            if (dSearch) dSearch.innerHTML = `<i class="fa-solid fa-magnifying-glass me-2"></i> ${isBn ? "অনুসন্ধান" : "Search"}`;
            if (dAbout) dAbout.innerHTML = `<i class="fa-solid fa-circle-info me-2"></i> ${isBn ? "অ্যাপ সম্পর্কে" : "About App"}`;
            if (dPrivacy) dPrivacy.innerHTML = `<i class="fa-solid fa-shield-halved me-2"></i> ${isBn ? "গোপনীয়তা নীতি" : "Privacy Policy"}`;
            if (dContact) dContact.innerHTML = `<i class="fa-solid fa-envelope me-2"></i> ${isBn ? "যোগাযোগ" : "Contact"}`;

            // Lang toggle button label
            const label = document.getElementById("langLabel");
            if (label) label.textContent = isBn ? "Ar+বাং" : "Ar+En";
        }

        window.setQuranLanguage = function(lang) {
            localStorage.setItem("quran_lang", lang);
            applyLayoutLanguage(lang);
            window.dispatchEvent(new CustomEvent("quranLanguageChanged", { detail: { lang } }));
        };

        const initialLang = localStorage.getItem("quran_lang") || "bn";
        applyLayoutLanguage(initialLang);

        const langBtn = document.getElementById("langToggleBtn");
        if (langBtn) {
            langBtn.addEventListener("click", () => {
                const nextLang = (window.currentQuranLang || initialLang) === "bn" ? "en" : "bn";
                window.setQuranLanguage(nextLang);
            });
        }
    </script>

    @yield('scripts')
</body>
</html>
