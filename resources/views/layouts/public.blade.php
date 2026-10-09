<!DOCTYPE html>
<html lang="{{ app()->getLocale() == 'en' ? 'en' : 'bn' }}" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    
    @yield('meta', View::make('partials.seo-meta'))
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <!-- Quran Mazid Custom CSS -->
    @php
        $cssVer = file_exists(public_path('css/quranmazid.css')) ? filemtime(public_path('css/quranmazid.css')) : time();
        $dataVer = file_exists(public_path('js/quran-data.js')) ? filemtime(public_path('js/quran-data.js')) : time();
        $audioVer = file_exists(public_path('js/quran-audio.js')) ? filemtime(public_path('js/quran-audio.js')) : time();
        $readerVer = file_exists(public_path('js/quran-reader.js')) ? filemtime(public_path('js/quran-reader.js')) : time();
    @endphp
    <link href="{{ asset('css/quranmazid.css') }}?v={{ $cssVer }}" rel="stylesheet">
    
    <script>
        window.APP_BASE_URL = "{{ url('/') }}";
        window.currentQuranLang = localStorage.getItem("quran_lang") || "bn";
        window.toBanglaNumber = function(num) {
            if (num === null || num === undefined) return '';
            const bnNums = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
            return String(num).split('').map(d => bnNums[parseInt(d)] !== undefined ? bnNums[parseInt(d)] : d).join('');
        };
        window.formatNumberByLang = function(num, lang) {
            lang = lang || window.currentQuranLang || 'bn';
            return lang === 'bn' ? window.toBanglaNumber(num) : String(num);
        };
    </script>
    
    @yield('styles')
</head>
<body>

    <!-- Watermark Calligraphy & Ambient Orbs Background -->
    <div class="quran-bg-watermark">
        <div class="quran-bg-dots"></div>
        <div class="watermark-text-center">ٱلْقُرْآنُ ٱلْكَرِيمُ</div>
        <div class="watermark-text-left">اقْرَأْ بِاسْمِ رَبِّكَ</div>
        <div class="watermark-text-right">قُرْآنٌ مَجِيدٌ</div>
        <div class="glow-orb-top"></div>
        <div class="glow-orb-right"></div>
    </div>

    <!-- Floating Top Navigation Pill -->
    <header class="navbar-floating-container">
        <nav class="navbar-floating-pill">
            <!-- Brand Logo -->
            <a href="{{ route('public.home') }}" class="brand-logo-pill">
                <img src="{{ asset('favicon.png') }}" alt="Al Quran" class="brand-logo-img">
                <span class="font-bangla" id="navBrandText">আল কুরআন</span>
            </a>

            <!-- Center Navigation Links -->
            <div class="nav-links-pill d-none d-md-flex">
                <a href="{{ route('public.home') }}" id="navHome" class="nav-pill-item {{ request()->routeIs('public.home') ? 'active' : '' }}">হোম</a>
                <a href="{{ route('public.surah', 1) }}" id="navRead" class="nav-pill-item {{ request()->routeIs('public.surah') ? 'active' : '' }}">কুরআন পড়ুন</a>
                <a href="{{ route('public.quran.bangla') }}" id="navTafsir" class="nav-pill-item {{ request()->routeIs('public.quran.*') ? 'active' : '' }}">অনুবাদ ও তাফসীর</a>
                <a href="{{ route('public.dua') }}" id="navDua" class="nav-pill-item {{ request()->routeIs('public.dua') ? 'active' : '' }}">দোয়া</a>
                <a href="{{ route('public.prayer-times') }}" id="navPrayerTimes" class="nav-pill-item {{ request()->routeIs('public.prayer-times') ? 'active' : '' }}">নামাজের সময়</a>
                <a href="{{ route('public.bookmarks') }}" id="navBookmarks" class="nav-pill-item {{ request()->routeIs('public.bookmarks') ? 'active' : '' }}">বুকমার্ক</a>
                <a href="{{ route('public.search') }}" id="navSearch" class="nav-pill-item {{ request()->routeIs('public.search') ? 'active' : '' }}">অনুসন্ধান</a>
            </div>

            <!-- Right Action Buttons -->
            <div class="nav-actions-pill">
                <!-- Theme Toggle Button (Light = Sun, Dark = Moon) -->
                <button class="btn-icon-circle" id="themeToggleBtn" aria-label="Toggle Theme" title="Toggle Theme">
                    <i class="fa-solid fa-sun text-warning" id="themeIcon"></i>
                </button>

                <!-- Language Toggle (Icon Only) -->
                <button class="btn-icon-circle" id="langToggleBtn" aria-label="Toggle Language" title="Change Language">
                    <i class="fa-solid fa-globe"></i>
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
                <img src="{{ asset('favicon.png') }}" alt="Al Quran" class="brand-logo-img">
                <h5 class="offcanvas-title font-bangla mb-0">আল কুরআন</h5>
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
                <a href="{{ route('public.quran.bangla') }}" id="drawerBangla" class="nav-pill-item text-start p-3 rounded-3 {{ request()->routeIs('public.quran.bangla') ? 'active' : '' }}">
                    <i class="fa-solid fa-language me-2 text-emerald"></i> বাংলা অনুবাদসহ কুরআন
                </a>
                <a href="{{ route('public.quran.tafsir') }}" id="drawerTafsir" class="nav-pill-item text-start p-3 rounded-3 {{ request()->routeIs('public.quran.tafsir') ? 'active' : '' }}">
                    <i class="fa-solid fa-book-open-reader me-2 text-warning"></i> কুরআনের তাফসীর বাংলা
                </a>
                <a href="{{ route('public.dua') }}" id="drawerDua" class="nav-pill-item text-start p-3 rounded-3 {{ request()->routeIs('public.dua') ? 'active' : '' }}">
                    <i class="fa-solid fa-hands-praying me-2 text-primary"></i> ইসলামিক দোয়া ও মোনাজাত
                </a>
                <a href="{{ route('public.prayer-times') }}" id="drawerPrayerTimes" class="nav-pill-item text-start p-3 rounded-3 {{ request()->routeIs('public.prayer-times') ? 'active' : '' }}">
                    <i class="fa-solid fa-clock me-2 text-info"></i> নামাজের সময়সূচি (বাংলাদেশ)
                </a>
                <a href="{{ route('public.daily-ayah') }}" id="drawerDailyAyah" class="nav-pill-item text-start p-3 rounded-3 {{ request()->routeIs('public.daily-ayah') ? 'active' : '' }}">
                    <i class="fa-solid fa-sun me-2 text-warning"></i> প্রতিদিনের আয়াত
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

    <!-- Global SEO Footer (Included on all pages except Surah Reader) -->
    @if(!request()->routeIs('public.surah'))
        @include('partials.public-footer')
    @endif

    <!-- Global Floating Audio Player Bar (Hidden on Home Page) -->
    @if(!request()->routeIs('public.home') && !request()->is('/'))
    <div class="global-audio-player-bar" id="globalAudioBar">
        <!-- Track & Surah Meta -->
        <div class="audio-track-info" data-bs-toggle="modal" data-bs-target="#qariSelectModal" onclick="openQariModal()" style="cursor: pointer;" title="ক্বারী পরিবর্তন করতে ক্লিক করুন">
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
                <!-- Left Controls -->
                <div class="audio-side-ctrls left">
                    <button class="btn-audio-ctrl" id="audioLoopBtn" onclick="window.quranPlayer.toggleLoopMode()" title="Repeat / Loop">
                        <i class="fa-solid fa-repeat"></i>
                    </button>
                    <button class="btn-audio-ctrl" onclick="window.quranPlayer.prevAyah()" title="Previous Ayah">
                        <i class="fa-solid fa-backward"></i>
                    </button>
                </div>

                <!-- Center Play / Pause Button (100% Dead Center) -->
                <div class="audio-center-play">
                    <button class="btn-audio-play-main" id="audioMainPlayBtn" onclick="window.quranPlayer.togglePlayPause()" title="Play / Pause">
                        <i class="fa-solid fa-play"></i>
                    </button>
                </div>

                <!-- Right Controls -->
                <div class="audio-side-ctrls right">
                    <button class="btn-audio-ctrl" onclick="window.quranPlayer.nextAyah()" title="Next Ayah">
                        <i class="fa-solid fa-forward"></i>
                    </button>
                    <button class="btn-audio-ctrl audio-speed-ctrl-inline" onclick="window.quranPlayer.cycleSpeed()" title="Playback Speed">
                        <span id="audioInlineSpeed">1.0x</span>
                    </button>
                </div>
            </div>

            <!-- Progress Timeline Bar -->
            <div class="audio-progress-row">
                <span class="audio-time-label" id="audioCurTime">0:00</span>
                <input type="range" class="audio-seek-slider" id="audioSeekSlider" min="0" max="100" step="0.1" value="0">
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
    @endif

    <!-- Floating Download Al Quran from Play Store Button -->
    <div class="floating-playstore-container" id="floatingPlaystoreWrap">
        <a href="https://play.google.com/store/apps/details?id=com.engrsaad.alquran" 
           target="_blank" 
           rel="noopener noreferrer" 
           class="floating-playstore-btn" 
           title="Download Al Quran from Play Store">
            <span class="playstore-icon-wrap">
                <i class="fa-brands fa-google-play"></i>
            </span>
            <span class="playstore-btn-text">
                <span class="playstore-sub" id="playstoreSubText">GET ON PLAY STORE</span>
                <span class="playstore-title" id="playstoreTitleText">Al Quran App</span>
            </span>
        </a>
        <button type="button" class="playstore-close-btn" onclick="dismissPlaystoreBadge(event)" title="Close" aria-label="Dismiss">&times;</button>
    </div>

    <!-- Global Qari Selection Modal -->
    <div class="modal fade qari-select-modal" id="qariSelectModal" tabindex="-1" aria-labelledby="qariModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content qari-modal-content">
                <div class="modal-header qari-modal-header">
                    <div class="d-flex align-items-center gap-2">
                        <span class="qari-modal-icon-badge">
                            <i class="fa-solid fa-microphone-lines"></i>
                        </span>
                        <div>
                            <h6 class="modal-title font-bangla fw-bold mb-0" id="qariModalTitle">ক্বারী / তিলাওয়াতকারী নির্বাচন</h6>
                            <span class="qari-modal-sub font-bangla text-muted" id="qariModalSubTitle">পছন্দের ক্বারীর কণ্ঠে পবিত্র কুরআন তিলাওয়াত শুনুন</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close-modal" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="modal-body qari-modal-body">
                    <div class="qari-cards-list" id="qariModalCardsList">
                        <!-- Populated dynamically by JS -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Core Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/quran-data.js') }}?v={{ $dataVer }}"></script>
    <script src="{{ asset('js/quran-audio.js') }}?v={{ $audioVer }}"></script>
    <script src="{{ asset('js/quran-reader.js') }}?v={{ $readerVer }}"></script>

    <!-- Theme & Global Logic -->
    <script>
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
                // In light mode, show bright sun icon (like ref screenshot)
                // In dark mode, show moon icon
                icon.className = theme === "dark" ? "fa-solid fa-moon text-info" : "fa-solid fa-sun text-warning";
            }
            if (btn) {
                btn.title = theme === "dark" ? "Switch to Light Mode" : "Switch to Dark Mode";
            }
            const readerThemeIcon = document.querySelector("#readerThemeToggle i");
            if (readerThemeIcon) {
                readerThemeIcon.className = theme === "dark" ? "fa-solid fa-moon text-info" : "fa-solid fa-sun text-warning";
            }
        }

        // Global Layout Language Management
        function applyLayoutLanguage(lang) {
            window.currentQuranLang = lang;
            const isBn = lang === "bn";
            document.documentElement.setAttribute("data-lang", lang);

            // Navbar Brand
            const brandEls = document.querySelectorAll("#navBrandText, .offcanvas-title");
            brandEls.forEach(el => {
                el.textContent = isBn ? "আল কুরআন" : "Al Quran";
                if (isBn) {
                    el.classList.add("font-bangla");
                } else {
                    el.classList.remove("font-bangla");
                }
            });

            // Navbar items
            const navHome = document.getElementById("navHome");
            const navRead = document.getElementById("navRead");
            const navTafsir = document.getElementById("navTafsir");
            const navDua = document.getElementById("navDua");
            const navPrayerTimes = document.getElementById("navPrayerTimes");
            const navBookmarks = document.getElementById("navBookmarks");
            const navSearch = document.getElementById("navSearch");
            if (navHome) navHome.textContent = isBn ? "হোম" : "Home";
            if (navRead) navRead.textContent = isBn ? "কুরআন পড়ুন" : "Read Quran";
            if (navTafsir) navTafsir.textContent = isBn ? "অনুবাদ ও তাফসীর" : "Tafsir & Meaning";
            if (navDua) navDua.textContent = isBn ? "দোয়া" : "Dua";
            if (navPrayerTimes) navPrayerTimes.textContent = isBn ? "নামাজের সময়" : "Prayer Times";
            if (navBookmarks) navBookmarks.textContent = isBn ? "বুকমার্ক" : "Bookmarks";
            if (navSearch) navSearch.textContent = isBn ? "অনুসন্ধান" : "Search";

            // Mobile Drawer
            const dHome = document.getElementById("drawerHome");
            const dRead = document.getElementById("drawerRead");
            const dBangla = document.getElementById("drawerBangla");
            const dTafsir = document.getElementById("drawerTafsir");
            const dDua = document.getElementById("drawerDua");
            const dPrayerTimes = document.getElementById("drawerPrayerTimes");
            const dDailyAyah = document.getElementById("drawerDailyAyah");
            const dBookmarks = document.getElementById("drawerBookmarks");
            const dSearch = document.getElementById("drawerSearch");
            const dAbout = document.getElementById("drawerAbout");
            const dPrivacy = document.getElementById("drawerPrivacy");
            const dContact = document.getElementById("drawerContact");
            if (dHome) dHome.innerHTML = `<i class="fa-solid fa-house me-2"></i> ${isBn ? "হোম" : "Home"}`;
            if (dRead) dRead.innerHTML = `<i class="fa-solid fa-book-quran me-2"></i> ${isBn ? "কুরআন পড়ুন" : "Read Quran"}`;
            if (dBangla) dBangla.innerHTML = `<i class="fa-solid fa-language me-2 text-emerald"></i> ${isBn ? "বাংলা অনুবাদসহ কুরআন" : "Quran Translation"}`;
            if (dTafsir) dTafsir.innerHTML = `<i class="fa-solid fa-book-open-reader me-2 text-warning"></i> ${isBn ? "কুরআনের তাফসীর বাংলা" : "Quran Tafsir"}`;
            if (dDua) dDua.innerHTML = `<i class="fa-solid fa-hands-praying me-2 text-primary"></i> ${isBn ? "ইসলামিক দোয়া ও মোনাজাত" : "Islamic Dua"}`;
            if (dPrayerTimes) dPrayerTimes.innerHTML = `<i class="fa-solid fa-clock me-2 text-info"></i> ${isBn ? "নামাজের সময়সূচি (বাংলাদেশ)" : "Prayer Times (BD)"}`;
            if (dDailyAyah) dDailyAyah.innerHTML = `<i class="fa-solid fa-sun me-2 text-warning"></i> ${isBn ? "প্রতিদিনের আয়াত" : "Daily Verse"}`;
            if (dBookmarks) dBookmarks.innerHTML = `<i class="fa-solid fa-bookmark me-2"></i> ${isBn ? "সংরক্ষিত আয়াত / বুকমার্ক" : "Saved Bookmarks"}`;
            if (dSearch) dSearch.innerHTML = `<i class="fa-solid fa-magnifying-glass me-2"></i> ${isBn ? "অনুসন্ধান" : "Search"}`;
            if (dAbout) dAbout.innerHTML = `<i class="fa-solid fa-circle-info me-2"></i> ${isBn ? "অ্যাপ সম্পর্কে" : "About App"}`;
            if (dPrivacy) dPrivacy.innerHTML = `<i class="fa-solid fa-shield-halved me-2"></i> ${isBn ? "গোপনীয়তা নীতি" : "Privacy Policy"}`;
            if (dContact) dContact.innerHTML = `<i class="fa-solid fa-envelope me-2"></i> ${isBn ? "যোগাযোগ" : "Contact"}`;

            // Lang toggle button tooltip
            const langToggle = document.getElementById("langToggleBtn");
            if (langToggle) langToggle.title = isBn ? "Switch to English" : "বাংলায় পরিবর্তন করুন";

            // Audio Player Bar defaults if not active
            const audioSurahName = document.getElementById("audioSurahName");
            if (audioSurahName && (!window.quranPlayer || !window.quranPlayer.isPlaying)) {
                audioSurahName.textContent = isBn ? "সূরা আল-ফাতিহা (১)" : "Surah Al-Faatiha (1)";
            }

            // Floating Play Store Badge
            const psSub = document.getElementById("playstoreSubText");
            const psTitle = document.getElementById("playstoreTitleText");
            if (psSub) psSub.textContent = isBn ? "প্লে-স্টোর থেকে ডাউনলোড" : "GET ON PLAY STORE";
            if (psTitle) psTitle.textContent = isBn ? "আল কুরআন অ্যাপ" : "Al Quran App";
        }

        window.dismissPlaystoreBadge = function(e) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }
            const wrap = document.getElementById("floatingPlaystoreWrap");
            if (wrap) {
                wrap.style.display = "none";
                sessionStorage.setItem("playstore_btn_dismissed", "1");
            }
        };

        if (sessionStorage.getItem("playstore_btn_dismissed") === "1") {
            const wrap = document.getElementById("floatingPlaystoreWrap");
            if (wrap) wrap.style.display = "none";
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

        // ==========================================
        // Global Qari Modal & Reciter Switcher Logic
        // ==========================================
        window.openQariModal = function() {
            window.renderQariModalCards();
            const modalEl = document.getElementById("qariSelectModal");
            if (modalEl && window.bootstrap) {
                const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.show();
            }
        };

        window.renderQariModalCards = function() {
            const list = document.getElementById("qariModalCardsList");
            if (!list || !window.QURAN_DATA || !window.QURAN_DATA.reciters) return;

            const currentReciter = localStorage.getItem("quran_reciter") || "Alafasy_128kbps";
            const lang = window.currentQuranLang || localStorage.getItem("quran_lang") || "bn";
            const isBn = lang === "bn";

            const mTitle = document.getElementById("qariModalTitle");
            const mSub = document.getElementById("qariModalSubTitle");
            if (mTitle) mTitle.textContent = isBn ? "ক্বারী / তিলাওয়াতকারী নির্বাচন" : "Select Quran Reciter (Qari)";
            if (mSub) mSub.textContent = isBn ? "পছন্দের ক্বারীর কণ্ঠে পবিত্র কুরআন তিলাওয়াত শুনুন" : "Listen to Quran recitation in your favorite Qari's voice";

            let html = "";
            window.QURAN_DATA.reciters.forEach(r => {
                const isActive = r.subfolder === currentReciter;
                const displayName = isBn && r.banglaName ? r.banglaName : r.name;
                const subName = isBn ? r.name : (r.banglaName || '');
                const styleText = isBn ? 'মুরাত্তাল তিলাওয়াত' : 'Murattal Recitation';

                html += `
                    <button type="button" class="qari-select-card ${isActive ? 'active' : ''}" onclick="window.onSelectQariCard('${r.subfolder}', '${r.name}')">
                        <img src="${r.photo || '{{ asset('images/reciters/alafasy.webp') }}'}" alt="${r.name}" class="qari-card-thumb">
                        <div class="qari-card-info">
                            <div class="qari-card-name-row">
                                <span class="qari-card-name ${isBn ? 'font-bangla' : ''}">${displayName}</span>
                                <span class="qari-card-arabic">${r.arabicName}</span>
                            </div>
                            <div class="qari-card-meta">${styleText}${subName ? ' • ' + subName : ''}</div>
                        </div>
                        <span class="qari-card-badge-active font-bangla">
                            <i class="fa-solid fa-circle-check"></i> ${isBn ? 'সক্রিয়' : 'Active'}
                        </span>
                        <span class="qari-card-radio-icon"><i class="fa-solid fa-check"></i></span>
                    </button>
                `;
            });

            list.innerHTML = html;
        };

        window.onSelectQariCard = function(subfolder, name) {
            if (window.quranPlayer) {
                window.quranPlayer.setReciter(subfolder, name);
            } else {
                localStorage.setItem("quran_reciter", subfolder);
                localStorage.setItem("quran_reciter_name", name);
            }

            const modalEl = document.getElementById("qariSelectModal");
            if (modalEl && window.bootstrap) {
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
            }

            window.updateAllQariUIPreviews();
        };

        window.updateAllQariUIPreviews = function() {
            if (!window.QURAN_DATA || !window.QURAN_DATA.reciters) return;
            const currentReciter = localStorage.getItem("quran_reciter") || "Alafasy_128kbps";
            const reciterObj = window.QURAN_DATA.reciters.find(r => r.subfolder === currentReciter) || window.QURAN_DATA.reciters[0];
            const lang = window.currentQuranLang || localStorage.getItem("quran_lang") || "bn";
            const isBn = lang === "bn";

            // Header circle avatar
            const headImg = document.getElementById("headerQariImg");
            if (headImg && reciterObj) {
                headImg.src = reciterObj.photo || "{{ asset('images/reciters/alafasy.webp') }}";
                headImg.alt = reciterObj.name;
            }

            // Reading settings drawer preview
            const setImg = document.getElementById("settingQariImg");
            const setName = document.getElementById("settingQariName");
            const setSub = document.getElementById("settingQariSub");
            if (setImg && reciterObj) {
                setImg.src = reciterObj.photo || "{{ asset('images/reciters/alafasy.webp') }}";
                setImg.alt = reciterObj.name;
            }
            if (setName && reciterObj) {
                setName.textContent = isBn && reciterObj.banglaName ? reciterObj.banglaName : reciterObj.name;
            }
            if (setSub && reciterObj) {
                setSub.textContent = reciterObj.arabicName;
            }

            // Global audio player bar reciter name
            const audioReciter = document.getElementById("audioReciterName");
            if (audioReciter && reciterObj) {
                audioReciter.textContent = isBn && reciterObj.banglaName ? reciterObj.banglaName : reciterObj.name;
            }
        };

        window.addEventListener("quranReciterChanged", () => {
            window.updateAllQariUIPreviews();
        });

        window.addEventListener("quranLanguageChanged", () => {
            window.updateAllQariUIPreviews();
        });

        document.addEventListener("DOMContentLoaded", () => {
            const modalEl = document.getElementById("qariSelectModal");
            if (modalEl) {
                modalEl.addEventListener("show.bs.modal", () => {
                    window.renderQariModalCards();
                });
            }

            const headerBtn = document.getElementById("headerQariBtn");
            if (headerBtn) {
                headerBtn.addEventListener("click", (e) => {
                    e.preventDefault();
                    window.openQariModal();
                });
            }

            const settingBtn = document.getElementById("settingQariBtn");
            if (settingBtn) {
                settingBtn.addEventListener("click", (e) => {
                    e.preventDefault();
                    window.openQariModal();
                });
            }

            setTimeout(() => {
                window.updateAllQariUIPreviews();
            }, 100);
        });
    </script>

    @yield('scripts')
</body>
</html>
