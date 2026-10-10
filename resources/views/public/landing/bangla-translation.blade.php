@extends('layouts.public')

@section('meta')
    @include('partials.seo-meta', [
        'metaTitle' => 'Al Quran Plus - বাংলা অনুবাদসহ আল কুরআন ও ১১৪ সূরা',
        'metaDesc' => 'Al Quran Plus-এ পবিত্র কুরআনের ১১৪টি সূরার সহজ বাংলা অনুবাদ, বিশুদ্ধ আরবি পাঠ এবং বিশ্বখ্যাত ক্বারীদের কণ্ঠে সুমধুর অডিও তিলাওয়াত শুনুন ও অনলাইনে পড়ুন।',
        'metaKeywords' => 'বাংলা অনুবাদসহ আল কুরআন, কুরআন শরীফ বাংলা, Al Quran Bangla Translation, বাংলা কুরআন, কুরআনের অর্থ, ১১৪ সূরা বাংলা অনুবাদ',
        'canonicalUrl' => url('/quran/bangla-translation')
    ])
@endsection

@section('content')
<div class="container py-5" style="max-width: 1200px; margin: 0 auto; padding-top: 100px !important;">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('public.home') }}" class="text-decoration-none text-muted">হোম</a></li>
            <li class="breadcrumb-item active text-emerald" aria-current="page">বাংলা অনুবাদসহ আল কুরআন</li>
        </ol>
    </nav>

    <!-- Hero Header -->
    <div class="text-center mb-5">
        <span class="badge bg-emerald-subtle text-emerald px-3 py-2 rounded-pill font-bangla mb-3">
            <i class="fa-solid fa-book-quran me-1"></i> আল কুরআনুল কারীম • ১১৪ সূরা
        </span>
        <h1 class="display-6 fw-bold font-bangla text-primary mb-3">
            বাংলা অনুবাদসহ আল কুরআন
        </h1>
        <p class="lead text-muted mx-auto" style="max-width: 780px; font-size: 1.05rem;">
            সহজ, সাবলীল ও প্রামাণ্য বাংলা অর্থসহ পবিত্র কুরআনের ১১৪টি সূরা অধ্যয়ন করুন। বিশুদ্ধ আরবি পাঠের পাশাপাশি আয়াতভিত্তিক বাংলা অনুবাদ ও বিশ্বখ্যাত ক্বারীদের কণ্ঠে সুরমধুর তিলাওয়াত শুনুন।
        </p>
    </div>

    <!-- Quick Stats -->
    <div class="row g-3 mb-5 text-center">
        <div class="col-6 col-md-3">
            <div class="p-3 rounded-4" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                <div class="fs-4 fw-bold text-emerald">১১৪টি</div>
                <div class="small text-muted">মোট সূরা</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-3 rounded-4" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                <div class="fs-4 fw-bold text-primary">৬,২৩৬টি</div>
                <div class="small text-muted">পবিত্র আয়াত</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-3 rounded-4" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                <div class="fs-4 fw-bold text-warning">৩০টি</div>
                <div class="small text-muted">পারা / সিপারা</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-3 rounded-4" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                <div class="fs-4 fw-bold text-info">৮ জন</div>
                <div class="small text-muted">আন্তর্জাতিক ক্বারী</div>
            </div>
        </div>
    </div>

    <!-- Surah Search Filter -->
    <div class="mb-4">
        <div class="position-relative" style="max-width: 500px; margin: 0 auto;">
            <i class="fa-solid fa-magnifying-glass position-absolute" style="left: 16px; top: 14px; color: var(--text-dim);"></i>
            <input type="text" id="surahTableSearch" class="form-control rounded-pill ps-5 py-2" placeholder="সূরার নাম (বাংলা / ইংরেজি) দিয়ে খুঁজুন..." oninput="filterSurahTable(this.value)" style="background: var(--bg-card); border-color: var(--border-color); color: var(--text-primary);">
        </div>
    </div>

    <!-- 114 Surahs Directory Table -->
    <div class="table-responsive rounded-4 shadow-sm mb-5" style="background: var(--bg-card); border: 1px solid var(--border-color);">
        <table class="table table-hover align-middle mb-0" id="surahDataTable" style="color: var(--text-primary);">
            <thead style="background: var(--bg-card); border-bottom: 2px solid var(--border-color);">
                <tr>
                    <th scope="col" class="py-3 px-3 text-center" style="width: 70px;">নং</th>
                    <th scope="col" class="py-3">সূরার নাম ও বাংলা অর্থ</th>
                    <th scope="col" class="py-3 text-end font-amiri">আরবি নাম</th>
                    <th scope="col" class="py-3 text-center">আয়াত সংখ্যা</th>
                    <th scope="col" class="py-3 text-center">অবতীর্ণ</th>
                    <th scope="col" class="py-3 text-end pe-3">তিলাওয়াত ও অনুবাদ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($surahs as $s)
                <tr class="surah-row" data-name="{{ strtolower($s['name']) }} {{ $s['bangla'] }} {{ $s['banglaMeaning'] }}">
                    <td class="text-center fw-bold text-muted">
                        {{ App\Services\QuranDataService::toBanglaNumber($s['id']) }}
                    </td>
                    <td>
                        <a href="{{ route('public.surah', $s['id']) }}" class="text-decoration-none fw-bold font-bangla d-block" style="color: var(--text-primary);">
                            সূরা {{ $s['bangla'] }} <span class="fw-normal text-muted small">({{ $s['name'] }})</span>
                        </a>
                        <span class="small text-muted font-bangla">অর্থ: {{ $s['banglaMeaning'] }}</span>
                    </td>
                    <td class="text-end font-amiri fs-5 text-emerald">
                        {{ $s['arabic'] }}
                    </td>
                    <td class="text-center small">
                        {{ App\Services\QuranDataService::toBanglaNumber($s['verses']) }}
                    </td>
                    <td class="text-center">
                        <span class="badge {{ $s['type'] == 'Makki' ? 'bg-warning text-dark' : 'bg-success' }} px-2 py-1 rounded-pill small">
                            {{ $s['type'] == 'Makki' ? 'মাক্কী' : 'মাদানী' }}
                        </span>
                    </td>
                    <td class="text-end pe-3">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('public.surah', $s['id']) }}" class="btn btn-sm btn-outline-success rounded-pill px-3" title="বাংলা অনুবাদ পড়ুন">
                                <i class="fa-solid fa-book-open me-1"></i> পড়ুন
                            </a>
                            <a href="{{ route('public.surah', $s['id']) }}?autoplay=1" class="btn btn-sm btn-success rounded-pill px-3" title="অডিও শুনুন">
                                <i class="fa-solid fa-play me-1"></i> অডিও
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Educational Content Block (Topical Authority) -->
    <div class="p-4 p-md-5 rounded-4 mb-5" style="background: var(--bg-card); border: 1px solid var(--border-color);">
        <h2 class="h4 fw-bold font-bangla text-primary mb-3">বাংলা অনুবাদে কুরআন অধ্যয়নের গুরুত্ব ও আদব</h2>
        <p class="text-muted" style="line-height: 1.8;">
            পবিত্র আল কুরআন বিশ্বমানবতার জন্য হেদায়েত ও আলোর উৎস। কেবল আরবি তিলাওয়াত ছাড়াও কুরআনের বাণীর প্রকৃত মর্মার্থ উপলব্ধি করার জন্য মাতৃভাষায় অর্থ অনুধাবন করা প্রতিটি সচেতন মুসলিমের জন্য অত্যন্ত জরুরি। আল্লাহ তা'আলা পবিত্র কুরআনে ইরশাদ করেন: <em>"এটি একটি বরকতময় কিতাব, যা আমি আপনার প্রতি অবতীর্ণ করেছি, যাতে তারা এর আয়াতসমূহ গভীরভাবে চিন্তা করে এবং বোধশক্তি সম্পন্ন ব্যক্তিরা উপদেশ গ্রহণ করে।"</em> (সূরা সাদ, আয়াত ২৯)।
        </p>
        <h3 class="h5 fw-bold font-bangla text-primary mt-4 mb-3">কুরআন পাঠের কিছু মৌলিক আদব:</h3>
        <ul class="text-muted d-flex flex-column gap-2" style="line-height: 1.7;">
            <li><i class="fa-solid fa-check text-emerald me-2"></i><strong>পবিত্রতা অর্জন:</strong> ওযু সহকারে কিতাব স্পর্শ করা এবং পরিচ্ছন্ন পরিবেশে তিলাওয়াত করা।</li>
            <li><i class="fa-solid fa-check text-emerald me-2"></i><strong>তা'আউয ও তাসমিয়াহ:</strong> তিলাওয়াতের শুরুতে বিতাড়িত শয়তান থেকে আল্লাহর আশ্রয় প্রার্থনা এবং বিসমিল্লাহ পাঠ করা।</li>
            <li><i class="fa-solid fa-check text-emerald me-2"></i><strong>ধীরস্থিরভাবে তিলাওয়াত:</strong> তাড়াহুড়ো না করে তারতীল সহকারে স্পষ্ট উচ্চারণে পাঠ করা।</li>
            <li><i class="fa-solid fa-check text-emerald me-2"></i><strong>অর্থ ও তাফসীর চিন্তা:</strong> প্রতিটি আয়াতের বাংলা অর্থ মনোযোগ দিয়ে পড়া ও জীবনের সাথে মিলিয়ে আমল করা।</li>
        </ul>
    </div>

    <!-- FAQ Section -->
    <div class="mb-5">
        <h2 class="h4 fw-bold font-bangla text-center text-primary mb-4">সাধারণ জিজ্ঞাসা (FAQ)</h2>
        <div class="accordion" id="faqAccordion">
            @php $faqIdx = 0; @endphp
            @foreach($faqs as $q => $a)
            @php $faqIdx++; @endphp
            <div class="accordion-item mb-3 rounded-3 overflow-hidden" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                <h3 class="accordion-header" id="heading{{ $faqIdx }}">
                    <button class="accordion-button font-bangla fw-bold {{ $faqIdx > 1 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $faqIdx }}" aria-expanded="{{ $faqIdx == 1 ? 'true' : 'false' }}" aria-controls="collapse{{ $faqIdx }}" style="background: var(--bg-card); color: var(--text-primary);">
                        {{ $q }}
                    </button>
                </h3>
                <div id="collapse{{ $faqIdx }}" class="accordion-collapse collapse {{ $faqIdx == 1 ? 'show' : '' }}" aria-labelledby="heading{{ $faqIdx }}" data-bs-parent="#faqAccordion">
                    <div class="accordion-body text-muted font-bangla small" style="line-height: 1.8;">
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
