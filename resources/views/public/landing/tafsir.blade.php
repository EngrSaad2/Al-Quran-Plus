@extends('layouts.public')

@section('meta')
    @include('partials.seo-meta', [
        'metaTitle' => 'Al Quran Plus - কুরআনের তাফসীর বাংলা (ইবনে কাসীর ও মাআরিফ)',
        'metaDesc' => 'Al Quran Plus-এ পড়ুন সহজ বাংলা তাফসীর। তাফসীরে ইবনে কাসীর ও মাআরিফুল কুরআনের আলোকে ১১৪টি সূরার আয়াতভিত্তিক ব্যাখ্যা, শানে নুযূল ও শিক্ষা।',
        'metaKeywords' => 'কুরআনের তাফসীর বাংলা, তাফসীর ইবনে কাসীর, মাআরিফুল কুরআন, Quran Tafsir Bangla, শানে নুযূল, তাফসীর অধ্যয়ন',
        'canonicalUrl' => url('/quran/tafsir/bangla')
    ])
@endsection

@section('content')
<div class="container py-5" style="max-width: 1200px; margin: 0 auto; padding-top: 100px !important;">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('public.home') }}" class="text-decoration-none text-muted">হোম</a></li>
            <li class="breadcrumb-item active text-emerald" aria-current="page">কুরআনের তাফসীর বাংলা</li>
        </ol>
    </nav>

    <!-- Hero Header -->
    <div class="text-center mb-5">
        <span class="badge bg-emerald-subtle text-emerald px-3 py-2 rounded-pill font-bangla mb-3">
            <i class="fa-solid fa-book-open-reader me-1"></i> আল কুরআনের গভীর অনুধাবন
        </span>
        <h1 class="display-6 fw-bold font-bangla text-primary mb-3">
            কুরআনের তাফসীর বাংলা (Quran Tafsir in Bangla)
        </h1>
        <p class="lead text-muted mx-auto" style="max-width: 780px; font-size: 1.05rem;">
            পবিত্র কুরআনের আয়াতসমূহের গভীর মর্মার্থ, শানে নুযূল এবং বিধান অনুধাবনের জন্য প্রামাণ্য তাফসীর অধ্যয়ন। তাফসীরে ইবনে কাসীর ও মাআরিফুল কুরআনের বিশুদ্ধ ব্যাখ্যায় আলোকিত হোক আপনার জীবন।
        </p>
    </div>

    <!-- Featured Tafsir Surahs Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="h-100 p-4 rounded-4" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="badge bg-warning text-dark rounded-pill">সূরা ১</span>
                    <span class="font-amiri fs-4 text-emerald">الفاتحة</span>
                </div>
                <h3 class="h5 fw-bold font-bangla mb-2">সূরা আল-ফাতিহা</h3>
                <p class="small text-muted mb-3">
                    উম্মুল কুরআন বা কুরআনের জননী। এতে আল্লাহর হামদ, তাওহীদের মূলনীতি এবং সিরাতুল মুস্তাকিমের দিকনির্দেশনার অদ্বিতীয় আলোচনা রয়েছে।
                </p>
                <a href="{{ route('public.surah', 1) }}" class="btn btn-sm btn-outline-success rounded-pill px-3">
                    তাফসীর ও অনুবাদ পড়ুন <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="h-100 p-4 rounded-4" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="badge bg-warning text-dark rounded-pill">সূরা ১৮</span>
                    <span class="font-amiri fs-4 text-emerald">الكهف</span>
                </div>
                <h3 class="h5 fw-bold font-bangla mb-2">সূরা আল-কাহফ</h3>
                <p class="small text-muted mb-3">
                    দাজ্জালের চার প্রকার ফিতনা (ঈমান, সম্পদ, জ্ঞান ও ক্ষমতা) থেকে বাঁচার দিকনির্দেশনা এবং আসহাবে কাহাফের অনন্য ইতিহাস।
                </p>
                <a href="{{ route('public.surah', 18) }}" class="btn btn-sm btn-outline-success rounded-pill px-3">
                    তাফসীর ও অনুবাদ পড়ুন <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="h-100 p-4 rounded-4" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="badge bg-warning text-dark rounded-pill">সূরা ৩৬</span>
                    <span class="font-amiri fs-4 text-emerald">يس</span>
                </div>
                <h3 class="h5 fw-bold font-bangla mb-2">সূরা ইয়াসিন</h3>
                <p class="small text-muted mb-3">
                    কুরআনের হৃৎপিণ্ড। তাওহীদ, রিসালাত এবং পরকালের অকাট্য যুক্তি ও পুনরুত্থানের প্রমাণ সংবলিত অত্যন্ত হৃদয়স্পর্শী সূরা।
                </p>
                <a href="{{ route('public.surah', 36) }}" class="btn btn-sm btn-outline-success rounded-pill px-3">
                    তাফসীর ও অনুবাদ পড়ুন <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="h-100 p-4 rounded-4" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="badge bg-warning text-dark rounded-pill">সূরা ৫৫</span>
                    <span class="font-amiri fs-4 text-emerald">الرحمن</span>
                </div>
                <h3 class="h5 fw-bold font-bangla mb-2">সূরা আর-রহমান</h3>
                <p class="small text-muted mb-3">
                    আল্লাহর অপার অনুকম্পা ও নিয়ামতের স্মরণ। "অতএব তোমরা তোমাদের প্রতিপালকের কোন্ কোন্ নেয়ামতকে অস্বীকার করবে?" — ৩১ বার বিঘোষিত বাণী।
                </p>
                <a href="{{ route('public.surah', 55) }}" class="btn btn-sm btn-outline-success rounded-pill px-3">
                    তাফসীর ও অনুবাদ পড়ুন <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="h-100 p-4 rounded-4" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="badge bg-warning text-dark rounded-pill">সূরা ৬৭</span>
                    <span class="font-amiri fs-4 text-emerald">الملك</span>
                </div>
                <h3 class="h5 fw-bold font-bangla mb-2">সূরা আল-মুলক</h3>
                <p class="small text-muted mb-3">
                    কবরের আযাব থেকে মুক্তিদানকারী সূরা। সৃষ্টিজগতের নিখুঁত ভারসাম্য ও মানুষের সৃষ্টিগত উদ্দেশ্যের গভীর তাফসীর।
                </p>
                <a href="{{ route('public.surah', 67) }}" class="btn btn-sm btn-outline-success rounded-pill px-3">
                    তাফসীর ও অনুবাদ পড়ুন <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="h-100 p-4 rounded-4" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="badge bg-warning text-dark rounded-pill">সূরা ৫৬</span>
                    <span class="font-amiri fs-4 text-emerald">الواقعة</span>
                </div>
                <h3 class="h5 fw-bold font-bangla mb-2">সূরা আল-ওয়াকিয়াহ</h3>
                <p class="small text-muted mb-3">
                    কিয়ামতের অবশ্যম্ভাবী ঘটনা এবং মানুষের তিন ভাগে বিভক্ত হওয়ার বর্ণনা (মুকাররাবূন, আসহাবুল ইয়ামীন, ও আসহাবুশ শিমাল)।
                </p>
                <a href="{{ route('public.surah', 56) }}" class="btn btn-sm btn-outline-success rounded-pill px-3">
                    তাফসীর ও অনুবাদ পড়ুন <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Educational Commentary on Classic Tafsir Works -->
    <div class="p-4 p-md-5 rounded-4 mb-5" style="background: var(--bg-card); border: 1px solid var(--border-color);">
        <h2 class="h4 fw-bold font-bangla text-primary mb-3">বাংলায় সমাদৃত নির্ভরযোগ্য তাফসীর গ্রন্থসমূহ</h2>
        <div class="row g-4 mt-2">
            <div class="col-md-6">
                <div class="border-start border-3 border-success ps-3">
                    <h3 class="h5 fw-bold font-bangla text-primary">১. তাফসীরে ইবনে কাসীর (অনুবাদ)</h3>
                    <p class="small text-muted" style="line-height: 1.7;">
                        আল্লামা ইমাদুদ্দীন ইবনে কাসীর (রহ.) রচিত বিশ্বখ্যাত তাফসীর। এটি 'তাফসীর বিল মা'ছূর' বা কুরআন ও হাদিসের আলোকে কুরআনের ব্যাখ্যায় শীর্ষস্থানীয়। ইসলামিক ফাউন্ডেশন বাংলাদেশ সহ প্রখ্যাত ওলামায়ে কেরাম কর্তৃক এর নির্ভরযোগ্য বাংলা অনুবাদ প্রকাশিত হয়েছে।
                    </p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="border-start border-3 border-success ps-3">
                    <h3 class="h5 fw-bold font-bangla text-primary">২. মাআরিফুল কুরআন (মুফতী মুহাম্মদ শফী রহ.)</h3>
                    <p class="small text-muted" style="line-height: 1.7;">
                        সাধারণ পাঠক ও গবেষক উভয়ের জন্যই অত্যন্ত সহজবোধ্য ও প্রামাণ্য গ্রন্থ। মাওলানা মুহিউদ্দীন খান (রহ.) অনূদিত বাংলা সংস্করণটি ইসলামিক ফাউন্ডেশন থেকে ৮ খণ্ডে প্রকাশিত এবং প্রতিটি আয়াতের সমসাময়িক বাস্তবমুখী তাৎপর্য তুলে ধরে।
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- All Surahs Quick List -->
    <div class="mb-5">
        <h2 class="h4 fw-bold font-bangla text-center text-primary mb-4">সকল ১১৪ সূরার তাফসীর ও অনুবাদ লিঙ্ক</h2>
        <div class="row g-2">
            @foreach($surahs as $s)
            <div class="col-6 col-md-4 col-lg-3">
                <a href="{{ route('public.surah', $s['id']) }}" class="p-2 d-flex align-items-center justify-content-between rounded-3 text-decoration-none" style="background: var(--bg-card); border: 1px solid var(--border-color); color: var(--text-primary);">
                    <span class="small font-bangla">{{ App\Services\QuranDataService::toBanglaNumber($s['id']) }}. সূরা {{ $s['bangla'] }}</span>
                    <span class="font-amiri text-emerald small">{{ $s['arabic'] }}</span>
                </a>
            </div>
            @endforeach
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="mb-5">
        <h2 class="h4 fw-bold font-bangla text-center text-primary mb-4">তাফসীর বিষয়ক সাধারণ জিজ্ঞাসা (FAQ)</h2>
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
@endsection
