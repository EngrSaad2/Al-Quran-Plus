@extends('layouts.public')

@section('title', 'Bookmarks — Quran Mazid')

@section('content')

<div class="container py-5" style="max-width: 900px; margin-top: 60px;">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="display-6 fw-bold font-bangla text-primary mb-1" id="bookmarksTitle">
                <i class="fa-solid fa-bookmark me-2"></i> সংরক্ষিত বুকমার্ক
            </h1>
            <p class="text-muted small mb-0" id="bookmarksSub">আপনার প্রিয় সূরা ও আয়াতসমূহ সহজে খুঁজে পেতে এখানে সংরক্ষিত থাকে।</p>
        </div>
        <button class="btn btn-sm btn-outline-danger rounded-pill px-3" id="clearAllBtn" onclick="clearAllBookmarks()">
            <i class="fa-solid fa-trash-can me-1"></i> সব মুছুন
        </button>
    </div>

    <!-- Bookmark Items Container -->
    <div class="section-box-container mb-5">
        <div id="bookmarksListContainer">
            <!-- Populated dynamically by JavaScript -->
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        renderBookmarks();
    });

    window.addEventListener("quranLanguageChanged", () => {
        renderBookmarks();
    });

    function renderBookmarks() {
        const container = document.getElementById("bookmarksListContainer");
        if (!container) return;

        const lang = window.currentQuranLang || localStorage.getItem("quran_lang") || "bn";
        const isBn = lang === "bn";

        // Update header texts
        const titleEl = document.getElementById("bookmarksTitle");
        const subEl = document.getElementById("bookmarksSub");
        const clearBtn = document.getElementById("clearAllBtn");

        if (titleEl) {
            titleEl.innerHTML = `<i class="fa-solid fa-bookmark me-2"></i> ${isBn ? "সংরক্ষিত বুকমার্ক" : "Saved Bookmarks"}`;
            if (isBn) titleEl.classList.add("font-bangla");
            else titleEl.classList.remove("font-bangla");
        }
        if (subEl) subEl.textContent = isBn ? "আপনার প্রিয় সূরা ও আয়াতসমূহ সহজে খুঁজে পেতে এখানে সংরক্ষিত থাকে।" : "Your favorite surahs and verses are saved here for easy access.";
        if (clearBtn) clearBtn.innerHTML = `<i class="fa-solid fa-trash-can me-1"></i> ${isBn ? "সব মুছুন" : "Clear All"}`;

        const bookmarks = JSON.parse(localStorage.getItem("quran_bookmarks") || "[]");

        if (bookmarks.length === 0) {
            container.innerHTML = `
                <div class="text-center py-5 text-muted">
                    <i class="fa-regular fa-bookmark fa-3x mb-3 text-dim"></i>
                    <h5 class="${isBn ? 'font-bangla' : ''}" style="color: var(--text-primary);">${isBn ? "এখনো কোন বুকমার্ক নেই" : "No Bookmarks Yet"}</h5>
                    <p class="small text-muted mb-4">${isBn ? "কুরআন পড়ার সময় পছন্দের সূরা বা আয়াতে বুকমার্ক আইকনে ক্লিক করে সংরক্ষণ করুন।" : "Click the bookmark icon on any surah or verse while reading to save it here."}</p>
                    <a href="{{ route('public.surah', 1) }}" class="btn-quran-primary" style="text-decoration:none;">
                        ${isBn ? "কুরআন পড়ুন" : "Read Quran"} <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>
            `;
            return;
        }

        const baseUrl = window.APP_BASE_URL || '';
        let html = '<div class="d-flex flex-column gap-3">';

        bookmarks.forEach((b, idx) => {
            const isAyah = !!b.ayah;
            const targetUrl = isAyah ? `${baseUrl}/surah/${b.surah}#ayah-${b.ayah}` : `${baseUrl}/surah/${b.surah}`;
            const sMeta = window.QURAN_DATA ? window.QURAN_DATA.surahs.find(item => item.id === b.surah) : null;
            const sName = isBn ? (sMeta ? sMeta.bangla : (b.banglaName || b.surahName)) : (sMeta ? sMeta.name : (b.surahName || b.banglaName));
            const prefix = isBn ? "সূরা" : "Surah";
            const ayahLabel = isAyah ? (isBn ? `আয়াত ${window.toBanglaNumber(b.ayah)}` : `Verse ${b.ayah}`) : '';
            const title = isAyah ? `${prefix} ${sName} : ${ayahLabel}` : `${prefix} ${sName}`;
            const dateLabel = b.date ? (isBn ? `সংরক্ষণ তারিখ: ${b.date}` : `Saved: ${b.date}`) : '';

            html += `
                <div class="surah-card p-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="surah-num-box">${b.surah}</div>
                        <div>
                            <a href="${targetUrl}" class="text-decoration-none">
                                <h5 class="mb-0 ${isBn ? 'font-bangla' : ''} fw-bold hover-primary" style="color: var(--text-primary);">${title}</h5>
                            </a>
                            <p class="text-muted small mb-0">${dateLabel}</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a href="${targetUrl}" class="btn-icon-circle text-primary" title="${isBn ? 'পড়ুন' : 'Read'}">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        </a>
                        <button class="btn-icon-circle text-danger" onclick="removeBookmark(${idx})" title="${isBn ? 'মুছুন' : 'Delete'}">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>
            `;
        });

        html += '</div>';
        container.innerHTML = html;
    }

    function removeBookmark(index) {
        let bookmarks = JSON.parse(localStorage.getItem("quran_bookmarks") || "[]");
        bookmarks.splice(index, 1);
        localStorage.setItem("quran_bookmarks", JSON.stringify(bookmarks));
        renderBookmarks();
    }

    function clearAllBookmarks() {
        const lang = window.currentQuranLang || localStorage.getItem("quran_lang") || "bn";
        const msg = lang === "bn" ? "আপনি কি নিশ্চিত যে সকল সংরক্ষিত বুকমার্ক মুছে ফেলতে চান?" : "Are you sure you want to clear all saved bookmarks?";
        if (confirm(msg)) {
            localStorage.removeItem("quran_bookmarks");
            renderBookmarks();
        }
    }
</script>
@endsection

