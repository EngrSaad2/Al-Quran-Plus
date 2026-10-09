@extends('layouts.public')

@section('meta')
    @include('partials.seo-meta', [
        'metaTitle' => 'Holy Quran with English Translation (Sahih International) | Al Quran Plus',
        'metaDesc' => 'Read the Holy Quran online with Sahih International English translation and clear Arabic Uthmani script. All 114 Surahs with continuous audio recitation and verse references.',
        'metaKeywords' => 'Quran English Translation, Holy Quran Sahih International, Read Quran Online, Al Quran English, Surah with English Meaning',
        'canonicalUrl' => url('/quran/english-translation')
    ])
@endsection

@section('content')
<div class="container py-5" style="max-width: 1200px; margin: 0 auto; padding-top: 100px !important;">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('public.home') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item active text-emerald" aria-current="page">Quran with English Translation</li>
        </ol>
    </nav>

    <!-- Hero Header -->
    <div class="text-center mb-5">
        <span class="badge bg-emerald-subtle text-emerald px-3 py-2 rounded-pill mb-3">
            <i class="fa-solid fa-globe me-1"></i> The Noble Quran • 114 Surahs
        </span>
        <h1 class="display-6 fw-bold text-primary mb-3">
            Holy Quran with English Translation
        </h1>
        <p class="lead text-muted mx-auto" style="max-width: 780px; font-size: 1.05rem;">
            Explore the authentic teachings of the Holy Quran with crystal-clear Arabic calligraphy and the globally recognized Sahih International English translation. Synchronized audio recitation from leading international Qaris.
        </p>
    </div>

    <!-- Surah Search Filter -->
    <div class="mb-4">
        <div class="position-relative" style="max-width: 500px; margin: 0 auto;">
            <i class="fa-solid fa-magnifying-glass position-absolute" style="left: 16px; top: 14px; color: var(--text-dim);"></i>
            <input type="text" id="surahTableSearch" class="form-control rounded-pill ps-5 py-2" placeholder="Search by Surah name or English meaning..." oninput="filterSurahTable(this.value)" style="background: var(--bg-card); border-color: var(--border-color); color: var(--text-primary);">
        </div>
    </div>

    <!-- 114 Surahs Directory Table -->
    <div class="table-responsive rounded-4 shadow-sm mb-5" style="background: var(--bg-card); border: 1px solid var(--border-color);">
        <table class="table table-hover align-middle mb-0" id="surahDataTable" style="color: var(--text-primary);">
            <thead style="background: var(--bg-card); border-bottom: 2px solid var(--border-color);">
                <tr>
                    <th scope="col" class="py-3 px-3 text-center" style="width: 70px;">#</th>
                    <th scope="col" class="py-3">Surah Name & English Meaning</th>
                    <th scope="col" class="py-3 text-end font-amiri">Arabic Script</th>
                    <th scope="col" class="py-3 text-center">Verses</th>
                    <th scope="col" class="py-3 text-center">Revelation</th>
                    <th scope="col" class="py-3 text-end pe-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($surahs as $s)
                <tr class="surah-row" data-name="{{ strtolower($s['name']) }} {{ strtolower($s['englishMeaning']) }}">
                    <td class="text-center fw-bold text-muted">
                        {{ $s['id'] }}
                    </td>
                    <td>
                        <a href="{{ route('public.surah', $s['id']) }}" class="text-decoration-none fw-bold d-block" style="color: var(--text-primary);">
                            Surah {{ $s['name'] }}
                        </a>
                        <span class="small text-muted">{{ $s['englishMeaning'] }}</span>
                    </td>
                    <td class="text-end font-amiri fs-5 text-emerald">
                        {{ $s['arabic'] }}
                    </td>
                    <td class="text-center small">
                        {{ $s['verses'] }}
                    </td>
                    <td class="text-center">
                        <span class="badge {{ $s['type'] == 'Makki' ? 'bg-warning text-dark' : 'bg-success' }} px-2 py-1 rounded-pill small">
                            {{ $s['type'] }}
                        </span>
                    </td>
                    <td class="text-end pe-3">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('public.surah', $s['id']) }}" class="btn btn-sm btn-outline-success rounded-pill px-3" title="Read English Translation">
                                <i class="fa-solid fa-book-open me-1"></i> Read
                            </a>
                            <a href="{{ route('public.surah', $s['id']) }}?autoplay=1" class="btn btn-sm btn-success rounded-pill px-3" title="Listen Audio">
                                <i class="fa-solid fa-play me-1"></i> Audio
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Educational Guide -->
    <div class="p-4 p-md-5 rounded-4 mb-5" style="background: var(--bg-card); border: 1px solid var(--border-color);">
        <h2 class="h4 fw-bold text-primary mb-3">Understanding the Holy Quran in English</h2>
        <p class="text-muted" style="line-height: 1.8;">
            While the Holy Quran is revealed in the eloquent Arabic tongue, translations bridge the linguistic gap for millions of English-speaking Muslims and curious seekers around the globe. The Sahih International translation is prized for preserving the theological purity of the text while using accessible, contemporary English prose.
        </p>
        <p class="text-muted" style="line-height: 1.8;">
            As Allah Almighty reminds us in Surah Al-Qamar (54:17): <em>"And We have certainly made the Quran easy for remembrance, so is there any who will remember?"</em> Reading with understanding deepens one's faith, inspires noble conduct, and anchors the soul in transcendent purpose.
        </p>
    </div>

    <!-- FAQ Section -->
    <div class="mb-5">
        <h2 class="h4 fw-bold text-center text-primary mb-4">Frequently Asked Questions</h2>
        <div class="accordion" id="faqAccordion">
            @php $faqIdx = 0; @endphp
            @foreach($faqs as $q => $a)
            @php $faqIdx++; @endphp
            <div class="accordion-item mb-3 rounded-3 overflow-hidden" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                <h3 class="accordion-header" id="heading{{ $faqIdx }}">
                    <button class="accordion-button fw-bold {{ $faqIdx > 1 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $faqIdx }}" aria-expanded="{{ $faqIdx == 1 ? 'true' : 'false' }}" aria-controls="collapse{{ $faqIdx }}" style="background: var(--bg-card); color: var(--text-primary);">
                        {{ $q }}
                    </button>
                </h3>
                <div id="collapse{{ $faqIdx }}" class="accordion-collapse collapse {{ $faqIdx == 1 ? 'show' : '' }}" aria-labelledby="heading{{ $faqIdx }}" data-bs-parent="#faqAccordion">
                    <div class="accordion-body text-muted small" style="line-height: 1.8;">
                        {{ $a }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<script>
    function filterSurahTable(query) {
        const q = query.trim().toLowerCase();
        document.querySelectorAll('.surah-row').forEach(row => {
            const data = row.getAttribute('data-name');
            if (!q || (data && data.includes(q))) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
@endsection
