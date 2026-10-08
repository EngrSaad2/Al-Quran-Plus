@extends('layouts.public')

@section('title', 'Search — Quran Mazid')

@section('content')

<div class="container py-5" style="max-width: 900px; margin-top: 60px;">
    <!-- Search Bar -->
    <div class="text-center mb-5">
        <h1 class="display-6 fw-bold font-bangla text-primary mb-2">
            <i class="fa-solid fa-magnifying-glass me-2"></i> পবিত্র কুরআন অনুসন্ধান
        </h1>
        <p class="text-muted small mb-4">সূরার নাম (বাংলা/ইংরেজি/আরবি), সূরার নম্বর বা অর্থ দিয়ে সহজে অনুসন্ধান করুন।</p>

        <div class="hero-search-box max-w-700 mx-auto">
            <i class="fa-solid fa-magnifying-glass text-muted me-2"></i>
            <input type="text" id="liveSearchInput" class="hero-search-input" placeholder="সূরার নাম, নম্বর বা অর্থ লিখুন..." value="{{ $query }}" oninput="performSearch(this.value)" autofocus>
            <button class="hero-search-btn" onclick="performSearch(document.getElementById('liveSearchInput').value)">
                <span>খুঁজুন</span>
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

    function performSearch(query = "") {
        const container = document.getElementById("searchResultsGrid");
        const countLabel = document.getElementById("searchCountLabel");
        if (!container || !window.QURAN_DATA) return;

        const q = (query || "").trim().toLowerCase();

        let results = window.QURAN_DATA.surahs;
        if (q) {
            results = results.filter(s => 
                s.name.toLowerCase().includes(q) ||
                s.bangla.toLowerCase().includes(q) ||
                s.englishMeaning.toLowerCase().includes(q) ||
                s.banglaMeaning.toLowerCase().includes(q) ||
                s.arabic.includes(q) ||
                String(s.id) === q ||
                String(s.id).includes(q)
            );
        }

        if (countLabel) {
            countLabel.textContent = q ? `"${query}" এর জন্য ${results.length} টি ফলাফল পাওয়া গেছে` : `মোট ১১৪ টি সূরা`;
        }

        if (results.length === 0) {
            container.innerHTML = `
                <div class="text-center py-5 text-muted col-12" style="grid-column: 1 / -1;">
                    <i class="fa-solid fa-magnifying-glass fa-2x mb-3 text-dim"></i>
                    <h5 class="font-bangla text-light">কোন সূরা পাওয়া যায়নি</h5>
                    <p class="small text-muted">বানান সঠিক আছে কিনা যাচাই করুন অথবা অন্য কোন শব্দ লিখে খুঁজুন।</p>
                </div>
            `;
            return;
        }

        const baseUrl = window.APP_BASE_URL || '';
        let html = "";
        results.forEach(s => {
            const icon = s.type === "Makki" ? "🕋" : "🕌";
            const badgeClass = s.type === "Makki" ? "makki" : "madani";
            const badgeText = s.type === "Makki" ? "মাক্কী" : "মাদানী";

            html += `
                <a href="${baseUrl}/surah/${s.id}" class="surah-card">
                    <div class="surah-card-left">
                        <div class="surah-num-box">${s.id}</div>
                        <div class="surah-card-names">
                            <h4 class="surah-name-en mb-0">${s.bangla}</h4>
                            <p class="surah-meaning mb-0">${s.banglaMeaning} · ${s.name}</p>
                        </div>
                    </div>
                    <div class="surah-card-right">
                        <div class="surah-arabic-title">${s.arabic}</div>
                        <div class="surah-meta-row">
                            <span class="badge-origin-pill ${badgeClass}">${icon} ${badgeText}</span>
                            <span class="surah-verses-count">${s.verses} আয়াত</span>
                        </div>
                    </div>
                </a>
            `;
        });

        container.innerHTML = html;
    }
</script>
@endsection
