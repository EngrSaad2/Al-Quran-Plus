@extends('layouts.public')

@section('title', 'Search — Quran Mazid')

@section('content')

<div class="container py-5" style="max-width: 900px; margin-top: 60px;">
    <!-- Search Bar -->
    <div class="text-center mb-5">
        <h1 class="display-6 fw-bold font-bangla text-primary mb-2" id="searchHeaderTitle">
            <i class="fa-solid fa-magnifying-glass me-2"></i> পবিত্র কুরআন অনুসন্ধান
        </h1>
        <p class="text-muted small mb-4" id="searchHeaderSub">সূরার নাম (বাংলা/ইংরেজি/আরবি), সূরার নম্বর বা অর্থ দিয়ে সহজে অনুসন্ধান করুন।</p>

        <div class="hero-search-box max-w-700 mx-auto">
            <i class="fa-solid fa-magnifying-glass text-muted me-2"></i>
            <input type="text" id="liveSearchInput" class="hero-search-input" placeholder="সূরার নাম, নম্বর বা অর্থ লিখুন..." value="{{ $query }}" oninput="performSearch(this.value)" autofocus>
            <button class="hero-search-btn" onclick="performSearch(document.getElementById('liveSearchInput').value)">
                <span id="searchBtnSpan">খুঁজুন</span>
            </button>
        </div>
    </div>

    <!-- Search Results Header -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h6 class="font-bangla fw-bold text-muted mb-0" id="searchCountLabel">অনুসন্ধান ফলাফল</h6>
    </div>

    <!-- Results Container -->
    <div class="surah-cards-grid" id="searchResultsGrid">
        <!-- Dynamically rendered -->
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const initialQuery = "{{ $query }}";
        performSearch(initialQuery);
    });

    window.addEventListener("quranLanguageChanged", () => {
        const currentQ = document.getElementById("liveSearchInput") ? document.getElementById("liveSearchInput").value : "";
        performSearch(currentQ);
    });

    function performSearch(query = "") {
        const container = document.getElementById("searchResultsGrid");
        const countLabel = document.getElementById("searchCountLabel");
        if (!container || !window.QURAN_DATA) return;

        const lang = window.currentQuranLang || localStorage.getItem("quran_lang") || "bn";
        const isBn = lang === "bn";

        // Update static headers
        const titleEl = document.getElementById("searchHeaderTitle");
        const subEl = document.getElementById("searchHeaderSub");
        const inputEl = document.getElementById("liveSearchInput");
        const btnSpan = document.getElementById("searchBtnSpan");

        if (titleEl) {
            titleEl.innerHTML = `<i class="fa-solid fa-magnifying-glass me-2"></i> ${isBn ? "পবিত্র কুরআন অনুসন্ধান" : "Search Holy Quran"}`;
            if (isBn) titleEl.classList.add("font-bangla");
            else titleEl.classList.remove("font-bangla");
        }
        if (subEl) subEl.textContent = isBn ? "সূরার নাম (বাংলা/ইংরেজি/আরবি), সূরার নম্বর বা অর্থ দিয়ে সহজে অনুসন্ধান করুন।" : "Search by Surah name (English/Bengali/Arabic), number, or meaning.";
        if (inputEl) inputEl.placeholder = isBn ? "সূরার নাম, নম্বর বা অর্থ লিখুন..." : "Type surah name, number, or meaning...";
        if (btnSpan) btnSpan.textContent = isBn ? "খুঁজুন" : "Search";

        const q = (query || "").trim().toLowerCase();

        const normalizeStr = (str) => (str || '').toLowerCase().replace(/[-_']/g, '').replace(/aa/g, 'a').replace(/ee/g, 'i').replace(/oo/g, 'u');
        const normQ = normalizeStr(q);

        let results = window.QURAN_DATA.surahs;
        if (q) {
            results = results.filter(s => 
                s.name.toLowerCase().includes(q) ||
                normalizeStr(s.name).includes(normQ) ||
                s.bangla.toLowerCase().includes(q) ||
                s.englishMeaning.toLowerCase().includes(q) ||
                normalizeStr(s.englishMeaning).includes(normQ) ||
                s.banglaMeaning.toLowerCase().includes(q) ||
                s.arabic.includes(q) ||
                String(s.id) === q ||
                String(s.id).includes(q)
            );
        }

        if (countLabel) {
            if (isBn) countLabel.classList.add("font-bangla");
            else countLabel.classList.remove("font-bangla");

            if (q) {
                countLabel.textContent = isBn ? `"${query}" এর জন্য ${window.toBanglaNumber(results.length)} টি ফলাফল পাওয়া গেছে` : `Found ${results.length} results for "${query}"`;
            } else {
                countLabel.textContent = isBn ? `মোট ১১৪ টি সূরা` : `Total 114 Surahs`;
            }
        }

        if (results.length === 0) {
            container.innerHTML = `
                <div class="text-center py-5 text-muted col-12" style="grid-column: 1 / -1;">
                    <i class="fa-solid fa-magnifying-glass fa-2x mb-3 text-dim"></i>
                    <h5 class="${isBn ? 'font-bangla' : ''} text-light">${isBn ? "কোন সূরা পাওয়া যায়নি" : "No Surahs Found"}</h5>
                    <p class="small text-muted">${isBn ? "বানান সঠিক আছে কিনা যাচাই করুন অথবা অন্য কোন শব্দ লিখে খুঁজুন।" : "Please check your spelling or search with another keyword."}</p>
                </div>
            `;
            return;
        }

        const baseUrl = window.APP_BASE_URL || '';
        let html = "";
        results.forEach(s => {
            const icon = s.type === "Makki" ? "🕋" : "🕌";
            const badgeClass = s.type === "Makki" ? "makki" : "madani";
            const badgeText = isBn ? (s.type === "Makki" ? "মাক্কী" : "মাদানী") : s.type;
            const surahTitle = isBn ? s.bangla : s.name;
            const surahSub = isBn ? `${s.banglaMeaning} · ${s.name}` : s.englishMeaning;
            const versesText = isBn ? `${window.toBanglaNumber(s.verses)} আয়াত` : `${s.verses} Verses`;

            html += `
                <a href="${baseUrl}/surah/${s.id}" class="surah-card">
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
                            <span class="badge-origin-pill ${badgeClass}">${icon} ${badgeText}</span>
                            <span class="surah-verses-count">${versesText}</span>
                        </div>
                    </div>
                </a>
            `;
        });

        container.innerHTML = html;
    }
</script>
@endsection

