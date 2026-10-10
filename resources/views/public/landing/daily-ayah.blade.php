@extends('layouts.public')

@section('meta')
    @include('partials.seo-meta', [
        'metaTitle' => 'Al Quran Plus - প্রতিদিনের কুরআনের আয়াত ও বাংলা অর্থ',
        'metaDesc' => 'প্রতিদিনের জীবন গড়ার অনুপ্রেরণামূলক পবিত্র কুরআনের নির্বাচিত আয়াত। বিশুদ্ধ আরবি পাঠ, সহজ বাংলা অনুবাদ, ইংরেজি অর্থ ও গভীর আত্মশুদ্ধিমূলক জীবনোপদেশ।',
        'metaKeywords' => 'প্রতিদিনের আয়াত, Daily Quran Ayah Bangla, কুরআনের আয়াত ও অর্থ, ইসলামিক উপদেশ, অনুপ্রেরণামূলক আয়াত',
        'canonicalUrl' => url('/daily-ayah')
    ])
@endsection

@section('content')
<div class="container py-5" style="max-width: 1200px; margin: 0 auto; padding-top: 100px !important;">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('public.home') }}" class="text-decoration-none text-muted">হোম</a></li>
            <li class="breadcrumb-item active text-emerald" aria-current="page">প্রতিদিনের কুরআনের আয়াত</li>
        </ol>
    </nav>

    <!-- Hero Header -->
    <div class="text-center mb-5">
        <span class="badge bg-emerald-subtle text-emerald px-3 py-2 rounded-pill font-bangla mb-3">
            <i class="fa-solid fa-sun me-1"></i> আত্মশুদ্ধি ও অনুপ্রেরণা
        </span>
        <h1 class="display-6 fw-bold font-bangla text-primary mb-3">
            প্রতিদিনের কুরআনের আয়াত ও উপদেশ (Daily Quran Ayah)
        </h1>
        <p class="lead text-muted mx-auto" style="max-width: 780px; font-size: 1.05rem;">
            ব্যস্ত দৈনন্দিন জীবনে পবিত্র কুরআনের আলো ছড়াতে প্রতিদিনের নির্বাচিত আয়াত ও তার গভীর তাৎপর্য। ঈমানকে সতেজ রাখতে এবং অন্তরে প্রশান্তি লাভ করতে আয়াতটি অধ্যয়ন করুন।
        </p>
    </div>

    <!-- Today's Featured Ayah Card -->
    <div class="p-4 p-md-5 rounded-4 shadow-sm mb-5 position-relative overflow-hidden" style="background: var(--bg-card); border: 2px solid var(--primary-color);">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <span class="badge bg-success px-3 py-2 rounded-pill font-bangla fs-6">
                <i class="fa-solid fa-star me-1 text-warning"></i> আজকের নির্বাচিত আয়াত
            </span>
            <span class="text-muted font-bangla small">সূরা আশ-শারহ (৯৪), আয়াত ৫-৬</span>
        </div>

        <!-- Arabic Verse -->
        <div class="font-amiri fs-2 text-end text-emerald mb-4" dir="rtl" style="line-height: 2;">
            فَإِنَّ مَعَ الْعُسْرِ يُسْرًا ﴿٥﴾ إِنَّ مَعَ الْعُسْرِ يُسْرًا ﴿٦﴾
        </div>

        <!-- Bangla Meaning -->
        <div class="mb-3 font-bangla" style="font-size: 1.15rem; color: var(--text-primary); line-height: 1.8;">
            <strong>বাংলা অনুবাদ:</strong> "নিশ্চয় কষ্টের সাথেই রয়েছে স্বস্তি। নিশ্চয় কষ্টের সাথেই রয়েছে স্বস্তি।"
        </div>

        <!-- English Translation -->
        <div class="text-muted mb-4 small" style="line-height: 1.7;">
            <strong>English (Sahih International):</strong> "For indeed, with hardship [will be] ease. Indeed, with hardship [will be] ease."
        </div>

        <hr style="border-color: var(--border-color); margin: 25px 0;">

        <!-- Tafsir Reflection & Lesson -->
        <div class="mb-4">
            <h2 class="h5 fw-bold font-bangla text-primary mb-2">
                <i class="fa-solid fa-lightbulb text-warning me-2"></i> আজকের আয়াত থেকে জীবনের শিক্ষা
            </h2>
            <p class="text-muted font-bangla small mb-0" style="line-height: 1.8;">
                মহান আল্লাহ এই সূরায় স্পষ্টভাবে দু’বার প্রতিশ্রুতি দিয়েছেন যে কষ্টের সাথে সাথে স্বস্তি ও মুক্তিও প্রস্তুত থাকে। আরবি ব্যাকরণ অনুযায়ী 'আল-উসর' (কষ্ট) নির্দিষ্ট একটি রূপ, অথচ 'ইউসর' (স্বস্তি) অনির্দিষ্ট রূপ—যার অর্থ একটি কষ্ট কখনোই দুটি স্বস্তিকে পরাস্ত করতে পারে না। জীবনের যেকোনো সংকট, অসুস্থতা বা বিষণ্ণতায় আল্লাহর প্রতি আস্থা রাখুন, সুদিন অতি সন্নিকটে।
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('public.surah', 94) }}?autoplay=1" class="btn btn-success rounded-pill px-4">
                <i class="fa-solid fa-play me-1"></i> পূর্ণ সূরা শুনুন
            </a>
            <button class="btn btn-outline-secondary rounded-pill px-4" onclick="copyDuaText(this, 'فَإِنَّ مَعَ الْعُسْرِ يُسْرًا ﴿٥﴾ إِنَّ مَعَ الْعُسْرِ يُسْرًا ﴿٦﴾\n\nবাংলা অনুবাদ: নিশ্চয় কষ্টের সাথেই রয়েছে স্বস্তি। নিশ্চয় কষ্টের সাথেই রয়েছে স্বস্তি। [সূরা আশ-শারহ ৯৪:৫-৬]')">
                <i class="fa-regular fa-copy me-1"></i> আয়াতটি কপি করুন
            </button>
        </div>
    </div>

    <!-- Archive of Inspiring Verses -->
    <div class="mb-5">
        <h2 class="h4 fw-bold font-bangla text-primary mb-4">অনুপ্রেরণামূলক আরও কিছু পবিত্র আয়াত</h2>
        <div class="row g-4">
            <!-- Verse 1: Tawakkul -->
            <div class="col-md-4">
                <div class="h-100 p-4 rounded-4" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="badge bg-warning-subtle text-dark rounded-pill font-bangla">তাওয়াক্কুল ও ভরসা</span>
                        <span class="small text-muted font-bangla">সূরা তালাক ৬৫:৩</span>
                    </div>
                    <div class="font-amiri fs-5 text-end text-emerald mb-2" dir="rtl">
                        وَمَن يَتَوَكَّلْ عَلَى اللَّهِ فَهُوَ حَسْبُهُ
                    </div>
                    <div class="small font-bangla text-muted">
                        "আর যে ব্যক্তি আল্লাহর ওপর ভরসা করে, তার জন্য তিনিই যথেষ্ট।"
                    </div>
                </div>
            </div>

            <!-- Verse 2: Sabr -->
            <div class="col-md-4">
                <div class="h-100 p-4 rounded-4" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="badge bg-info-subtle text-info rounded-pill font-bangla">ধৈর্য ও সাহায্য</span>
                        <span class="small text-muted font-bangla">সূরা বাকারা ২:১৫৩</span>
                    </div>
                    <div class="font-amiri fs-5 text-end text-emerald mb-2" dir="rtl">
                        إِنَّ اللَّهَ مَعَ الصَّابِرِينَ
                    </div>
                    <div class="small font-bangla text-muted">
                        "নিশ্চয় আল্লাহ ধৈর্যশীলদের সাথে রয়েছেন।"
                    </div>
                </div>
            </div>

            <!-- Verse 3: Shukr -->
            <div class="col-md-4">
                <div class="h-100 p-4 rounded-4" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="badge bg-success-subtle text-success rounded-pill font-bangla">কৃতজ্ঞতা</span>
                        <span class="small text-muted font-bangla">সূরা ইব্রাহিম ১৪:৭</span>
                    </div>
                    <div class="font-amiri fs-5 text-end text-emerald mb-2" dir="rtl">
                        لَئِن شَكَرْتُمْ لَأَزِيدَنَّكُمْ
                    </div>
                    <div class="small font-bangla text-muted">
                        "যদি তোমরা কৃতজ্ঞতা স্বীকার করো, তবে আমি অবশ্যই তোমাদেরকে আরো বাড়িয়ে দেব।"
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function copyDuaText(btn, text) {
        navigator.clipboard.writeText(text).then(() => {
            const original = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-check text-success me-1"></i> কপি হয়েছে!';
            setTimeout(() => {
                btn.innerHTML = original;
            }, 2000);
        });
    }
</script>
@endsection
