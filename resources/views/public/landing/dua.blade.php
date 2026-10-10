@extends('layouts.public')

@section('meta')
    @include('partials.seo-meta', [
        'metaTitle' => 'Al Quran Plus - প্রয়োজনীয় ইসলামিক দোয়া ও মোনাজাত',
        'metaDesc' => 'Al Quran Plus-এ প্রয়োজনীয় ইসলামিক দোয়া ও মোনাজাত। আরবি পাঠ, বাংলা উচ্চারণ ও অর্থসহ দৈনন্দিন জীবনের মাসনুন দোয়া ও সকাল-সন্ধ্যার সহীহ জিকির।',
        'metaKeywords' => 'ইসলামিক দোয়া বাংলা, কুরআনের দোয়া, রাব্বানা দোয়া, সাইয়্যিদুল ইস্তেগফার, বিপদের দোয়া, দোয়ার অর্থ ও উচ্চারণ',
        'canonicalUrl' => url('/dua')
    ])
@endsection

@section('content')
<div class="container py-5" style="max-width: 1200px; margin: 0 auto; padding-top: 100px !important;">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('public.home') }}" class="text-decoration-none text-muted">হোম</a></li>
            <li class="breadcrumb-item active text-emerald" aria-current="page">ইসলামিক দোয়া ও মোনাজাত</li>
        </ol>
    </nav>

    <!-- Hero Header -->
    <div class="text-center mb-5">
        <span class="badge bg-emerald-subtle text-emerald px-3 py-2 rounded-pill font-bangla mb-3">
            <i class="fa-solid fa-hands-praying me-1"></i> আল কুরআন ও সহীহ সুন্নাহ
        </span>
        <h1 class="display-6 fw-bold font-bangla text-primary mb-3">
            দৈনন্দিন জীবনের প্রয়োজনীয় ইসলামিক দোয়া ও মোনাজাত
        </h1>
        <p class="lead text-muted mx-auto" style="max-width: 780px; font-size: 1.05rem;">
            রাসূলুল্লাহ (সা.) বলেছেন: <em>"দোয়াই হলো ইবাদত।"</em> (সুনানে তিরমিযী)। পবিত্র কুরআন ও সহীহ হাদিস থেকে সংকলিত বিশুদ্ধ দোয়া সমূহ আরবি পাঠ, বাংলা উচ্চারণ, অর্থ ও নির্ভরযোগ্য রেফারেন্সসহ পাঠ করুন।
        </p>
    </div>

    <!-- Dua Categories Nav Pills -->
    <div class="d-flex flex-wrap justify-content-center gap-2 mb-5">
        <a href="#rabbana" class="btn btn-outline-success rounded-pill px-3 py-2 small font-bangla">
            <i class="fa-solid fa-kaaba me-1"></i> রাব্বানা দোয়া (কুরআন)
        </a>
        <a href="#forgiveness" class="btn btn-outline-success rounded-pill px-3 py-2 small font-bangla">
            <i class="fa-solid fa-heart me-1"></i> ক্ষমা ও তাওবা
        </a>
        <a href="#protection" class="btn btn-outline-success rounded-pill px-3 py-2 small font-bangla">
            <i class="fa-solid fa-shield-halved me-1"></i> বিপদ ও দুশ্চিন্তা মুক্তি
        </a>
        <a href="#parents" class="btn btn-outline-success rounded-pill px-3 py-2 small font-bangla">
            <i class="fa-solid fa-people-roof me-1"></i> পিতা-মাতার জন্য দোয়া
        </a>
    </div>

    <!-- Section 1: Rabbana Duas from Quran -->
    <div class="mb-5" id="rabbana">
        <div class="d-flex align-items-center gap-2 mb-4 pb-2 border-bottom" style="border-color: var(--border-color) !important;">
            <i class="fa-solid fa-book-quran text-emerald fs-4"></i>
            <h2 class="h4 fw-bold font-bangla text-primary mb-0">পবিত্র কুরআনের শ্রেষ্ঠ রাব্বানা দোয়া</h2>
        </div>

        <div class="row g-4">
            <!-- Dua 1 -->
            <div class="col-md-6">
                <div class="h-100 p-4 rounded-4" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-success-subtle text-success rounded-pill font-bangla">ইহকাল ও পরকালের কল্যাণ</span>
                        <span class="small text-muted font-bangla">সূরা আল-বাকারা ২:২০১</span>
                    </div>
                    <div class="font-amiri fs-4 text-end text-emerald mb-3" dir="rtl" style="line-height: 1.8;">
                        رَبَّنَا آتِنَا فِي الدُّنْيَا حَسَنَةً وَفِي الْآخِرَةِ حَسَنَةً وَقِنَا عَذَابَ النَّارِ
                    </div>
                    <div class="small mb-2 font-bangla text-primary">
                        <strong>উচ্চারণ:</strong> রব্বানা- আ-তিনা- ফিদ্দুনইয়া- হাসানাতাওঁ ওয়া ফিল আ-খিরাতি হাসানাতাওঁ ওয়াক্বিনা- 'আযা-বান না-র।
                    </div>
                    <div class="small text-muted font-bangla mb-3">
                        <strong>অর্থ:</strong> হে আমাদের প্রতিপালক! আমাদেরকে দুনিয়াতেও কল্যাণ দান করুন এবং আখিরাতেও কল্যাণ দান করুন এবং আমাদেরকে জাহান্নামের আগুন থেকে রক্ষা করুন।
                    </div>
                    <button class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="copyDuaText(this, 'رَبَّنَا آتِنَا فِي الدُّنْيَا حَسَنَةً وَفِي الْآخِرَةِ حَسَنَةً وَقِنَا عَذَابَ النَّارِ\n\nঅর্থ: হে আমাদের প্রতিপালক! আমাদেরকে দুনিয়াতেও কল্যাণ দান করুন এবং আখিরাতেও কল্যাণ দান করুন এবং আমাদেরকে জাহান্নামের আগুন থেকে রক্ষা করুন। [সূরা বাকারা ২:২০১]')">
                        <i class="fa-regular fa-copy me-1"></i> কপি করুন
                    </button>
                </div>
            </div>

            <!-- Dua 2 -->
            <div class="col-md-6">
                <div class="h-100 p-4 rounded-4" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-success-subtle text-success rounded-pill font-bangla">ঈমানের অবিচলতা</span>
                        <span class="small text-muted font-bangla">সূরা আলে ইমরান ৩:৮</span>
                    </div>
                    <div class="font-amiri fs-4 text-end text-emerald mb-3" dir="rtl" style="line-height: 1.8;">
                        رَبَّنَا لَا تُزِغْ قُلُوبَنَا بَعْدَ إِذْ هَدَيْتَنَا وَهَبْ لَنَا مِن لَّدُنكَ رَحْمَةً ۚ إِنَّكَ أَنتَ الْوَهَّابُ
                    </div>
                    <div class="small mb-2 font-bangla text-primary">
                        <strong>উচ্চারণ:</strong> রব্বানা- লা- তুযিগ কুলূবানা- বা'দা ইয হাদাইতানা- ওয়া হাবলানা- মিল্লাদুনকা রহমাহ, ইন্নাকা আনতাল ওয়াহ্হা-ব।
                    </div>
                    <div class="small text-muted font-bangla mb-3">
                        <strong>অর্থ:</strong> হে আমাদের প্রতিপালক! সরল পথ প্রদর্শনের পর আপনি আমাদের অন্তরকে সত্যলঙ্ঘনকারী করবেন না এবং আপনার নিকট থেকে আমাদেরকে রহমত দান করুন; নিশ্চয় আপনি মহাদাতা।
                    </div>
                    <button class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="copyDuaText(this, 'رَبَّنَا لَا تُزِغْ قُلُوبَنَا بَعْدَ إِذْ هَدَيْتَنَا وَهَبْ لَنَا مِن لَّدُنكَ رَحْمَةً\n\nঅর্থ: হে আমাদের প্রতিপালক! সরল পথ প্রদর্শনের পর আপনি আমাদের অন্তরকে সত্যলঙ্ঘনকারী করবেন না। [সূরা আলে ইমরান ৩:৮]')">
                        <i class="fa-regular fa-copy me-1"></i> কপি করুন
                    </button>
                </div>
            </div>

            <!-- Dua 3 -->
            <div class="col-md-6">
                <div class="h-100 p-4 rounded-4" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-success-subtle text-success rounded-pill font-bangla">ধৈর্য ও অবিচলতা</span>
                        <span class="small text-muted font-bangla">সূরা আল-বাকারা ২:২৫০</span>
                    </div>
                    <div class="font-amiri fs-4 text-end text-emerald mb-3" dir="rtl" style="line-height: 1.8;">
                        رَبَّنَا أَفْرِغْ عَلَيْنَا صَبْرًا وَثَبِّتْ أَقْدَامَنَا وَانصُرْنَا عَلَى الْقَوْمِ الْكَافِرِينَ
                    </div>
                    <div class="small mb-2 font-bangla text-primary">
                        <strong>উচ্চারণ:</strong> রব্বানা- আফরিগ 'আলাইনা- সবরাওঁ ওয়া ছাব্বিত আক্বদা-মানা- ওয়ানসুরনা- 'আলাল ক্বওমিল কা-ফিরীন।
                    </div>
                    <div class="small text-muted font-bangla mb-3">
                        <strong>অর্থ:</strong> হে আমাদের প্রতিপালক! আমাদের ওপর ধৈর্য ঢেলে দিন, আমাদের পা দৃঢ় রাখুন এবং সত্যপ্রত্যাখ্যানকারী সম্প্রদায়ের বিরুদ্ধে আমাদেরকে বিজয়ী করুন।
                    </div>
                    <button class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="copyDuaText(this, 'رَبَّنَا أَفْرِغْ عَلَيْنَا صَبْرًا وَثَبِّتْ أَقْدَامَنَا\n\nঅর্থ: হে আমাদের প্রতিপালক! আমাদের ওপর ধৈর্য ঢেলে দিন এবং আমাদের পদযুগল দৃঢ় রাখুন। [সূরা বাকারা ২:২৫০]')">
                        <i class="fa-regular fa-copy me-1"></i> কপি করুন
                    </button>
                </div>
            </div>

            <!-- Dua 4 -->
            <div class="col-md-6">
                <div class="h-100 p-4 rounded-4" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-success-subtle text-success rounded-pill font-bangla">উত্তম পরিবার ও সন্তান</span>
                        <span class="small text-muted font-bangla">সূরা আল-ফুরকান ২৫:৭৪</span>
                    </div>
                    <div class="font-amiri fs-4 text-end text-emerald mb-3" dir="rtl" style="line-height: 1.8;">
                        رَبَّنَا هَبْ لَنَا مِنْ أَزْوَاجِنَا وَذُرِّيَّاتِنَا قُرَّةَ أَعْيُنٍ وَاجْعَلْنَا لِلْمُتَّقِينَ إِمَامًا
                    </div>
                    <div class="small mb-2 font-bangla text-primary">
                        <strong>উচ্চারণ:</strong> রব্বানা- হাব লানা- মিন আযওয়া-জিনা- ওয়া যুররিয়্যা-তিনা- কুররতা আ'ইউনিওঁ ওয়াজ'আলনা- লিলমুত্তাকীনা ইমা-মা-।
                    </div>
                    <div class="small text-muted font-bangla mb-3">
                        <strong>অর্থ:</strong> হে আমাদের প্রতিপালক! আমাদের স্ত্রীদের ও সন্তানদেরকে আমাদের নয়নের তৃপ্তিদানকারী করুন এবং আমাদেরকে মুত্তাকীদের নেতা বানান।
                    </div>
                    <button class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="copyDuaText(this, 'رَبَّنَا هَبْ لَنَا مِنْ أَزْوَاجِنَا وَذُرِّيَّاتِنَا قُرَّةَ أَعْيُنٍ\n\nঅর্থ: হে আমাদের পালনকর্তা! আমাদের স্ত্রীদের ও সন্তানদেরকে আমাদের নয়নের তৃপ্তিদানকারী করুন। [সূরা ফুরকান ২৫:৭৪]')">
                        <i class="fa-regular fa-copy me-1"></i> কপি করুন
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 2: Forgiveness & Tawbah -->
    <div class="mb-5" id="forgiveness">
        <div class="d-flex align-items-center gap-2 mb-4 pb-2 border-bottom" style="border-color: var(--border-color) !important;">
            <i class="fa-solid fa-heart text-danger fs-4"></i>
            <h2 class="h4 fw-bold font-bangla text-primary mb-0">ক্ষমা ও তাওবার শ্রেষ্ঠ দোয়া</h2>
        </div>

        <div class="row g-4">
            <!-- Sayyidul Istighfar -->
            <div class="col-12">
                <div class="p-4 p-md-5 rounded-4" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-warning text-dark rounded-pill font-bangla">সাইয়্যিদুল ইস্তিগফার (তওবার শ্রেষ্ঠ দোয়া)</span>
                        <span class="small text-muted">সহীহ বুখারী, হাদিস নং ৬৩০৬</span>
                    </div>
                    <div class="font-amiri fs-3 text-end text-emerald mb-4" dir="rtl" style="line-height: 2;">
                        اللَّهُمَّ أَنْتَ رَبِّي لَا إِلَهَ إِلَّا أَنْتَ، خَلَقْتَنِي وَأَنَا عَبْدُكَ، وَأَنَا عَلَى عَهْدِكَ وَوَعْدِكَ مَا اسْتَطَعْتُ، أَعُوذُ بِكَ مِنْ شَرِّ مَا صَنَعْتُ، أَبُوءُ لَكَ بِنِعْمَتِكَ عَلَيَّ، وَأَبُوءُ لَكَ بِذَنْبِي فَاغْفِرْ لِي، فَإِنَّهُ لَا يغْفِرُ الذُّنُوبَ إِلَّا أَنْتَ
                    </div>
                    <div class="mb-2 font-bangla text-primary">
                        <strong>উচ্চারণ:</strong> আল্লা-হুম্মা আনতা রব্বী, লা- ইলা-হা ইল্লা- আনতা, খালাক্বতানী ওয়া আনা 'আবদুকা, ওয়া আনা 'আলা- 'আহদিকা ওয়া ওয়া'দিকা মাসতাত্বা'তু। আ'ঊযু বিকা মিন শাররি মা- সনা'তু, আবূউ লাকা বিনি'মাতিকা 'আলাইয়্যা, ওয়া আবূউ লাকা বিযাম্বী, ফাগফির লী, ফাইন্নাহূ লা- ইয়াগফিরুয যুনূবা ইল্লা- আনতা।
                    </div>
                    <div class="text-muted font-bangla mb-4" style="line-height: 1.8;">
                        <strong>অর্থ:</strong> হে আল্লাহ! আপনিই আমার প্রতিপালক। আপনি ছাড়া সত্য কোনো উপাস্য নেই। আপনি আমাকে সৃষ্টি করেছেন এবং আমি আপনার বান্দা। আমি আমার সাধ্যানুযায়ী আপনার সাথে কৃত অঙ্গীকার ও প্রতিশ্রুতির ওপর কায়েম রয়েছি। আমি আমার কৃতকর্মের মন্দ থেকে আপনার আশ্রয় প্রার্থনা করছি। আমার ওপর আপনার যে নিয়ামত রয়েছে তা স্বীকার করছি এবং আমার অপরাধও স্বীকার করছি। অতএব আপনি আমাকে ক্ষমা করে দিন। নিশ্চয়ই আপনি ছাড়া আর কেউ গুনাহ ক্ষমা করতে পারে না।
                    </div>
                    <div class="p-3 rounded-3 mb-3 small text-muted font-bangla" style="background: rgba(16, 185, 129, 0.08); border-left: 4px solid var(--primary-color);">
                        <i class="fa-solid fa-circle-info me-1 text-emerald"></i> <strong>ফজিলত:</strong> রাসূলুল্লাহ (সা.) ইরশাদ করেন: "যে ব্যক্তি দিনে বিশ্বাসের সাথে এ দোয়া পাঠ করবে এবং সন্ধ্যা হওয়ার আগে মারা যাবে, সে জান্নাতবাসী হবে। আর যে রাতে এ দোয়া পাঠ করবে এবং ভোর হওয়ার আগে মারা যাবে, সেও জান্নাতবাসী হবে।" (বুখারী)
                    </div>
                    <button class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="copyDuaText(this, 'সাইয়্যিদুল ইস্তিগফার:\nاللَّهُمَّ أَنْتَ رَبِّي لَا إِلَهَ إِلَّا أَنْتَ...\nঅর্থ: হে আল্লাহ! আপনিই আমার প্রতিপালক। আপনি ছাড়া কোনো সত্য উপাস্য নেই... [সহীহ বুখারী: ৬৩০৬]')">
                        <i class="fa-regular fa-copy me-1"></i> দোয়াটি কপি করুন
                    </button>
                </div>
            </div>

            <!-- Dua Yunus -->
            <div class="col-md-6">
                <div class="h-100 p-4 rounded-4" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-danger-subtle text-danger rounded-pill font-bangla">দোয়ায়ে ইউনুস (বিপদ মুক্তির দোয়া)</span>
                        <span class="small text-muted font-bangla">সূরা আল-আম্বিয়া ২১:৮৭</span>
                    </div>
                    <div class="font-amiri fs-4 text-end text-emerald mb-3" dir="rtl" style="line-height: 1.8;">
                        لَّا إِلَٰهَ إِلَّا أَنتَ سُبْحَانَكَ إِنِّي كُنتُ مِنَ الظَّالِمِينَ
                    </div>
                    <div class="small mb-2 font-bangla text-primary">
                        <strong>উচ্চারণ:</strong> লা- ইলা-হা ইল্লা- আনতা সুবহা-নাকা ইন্নী কুনতু মিনায য-লিমীন।
                    </div>
                    <div class="small text-muted font-bangla mb-3">
                        <strong>অর্থ:</strong> আপনি ছাড়া কোনো সত্য উপাস্য নেই, আপনি মহাপবিত্র; নিশ্চয়ই আমি অপরাধীদের অন্তর্ভুক্ত হয়ে গেছি।
                    </div>
                    <button class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="copyDuaText(this, 'لَّا إِلَٰهَ إِلَّا أَنتَ سُبْحَانَكَ إِنِّي كُنتُ مِنَ الظَّالِمِينَ\n\nঅর্থ: আপনি ছাড়া কোনো সত্য উপাস্য নেই, আপনি মহাপবিত্র; নিশ্চয়ই আমি সীমালঙ্ঘনকারীদের অন্তর্ভুক্ত। [সূরা আম্বিয়া ২১:৮৭]')">
                        <i class="fa-regular fa-copy me-1"></i> কপি করুন
                    </button>
                </div>
            </div>

            <!-- Adam AS Tawbah -->
            <div class="col-md-6">
                <div class="h-100 p-4 rounded-4" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-danger-subtle text-danger rounded-pill font-bangla">আদম (আ.)-এর তওবার দোয়া</span>
                        <span class="small text-muted font-bangla">সূরা আল-আ'রাফ ৭:২৩</span>
                    </div>
                    <div class="font-amiri fs-4 text-end text-emerald mb-3" dir="rtl" style="line-height: 1.8;">
                        رَبَّنَا ظَلَمْنَا أَنفُسَنَا وَإِن لَّمْ تَغْفِرْ لَنَا وَتَرْحَمْنَا لَنَكُونَنَّ مِنَ الْخَاسِرِينَ
                    </div>
                    <div class="small mb-2 font-bangla text-primary">
                        <strong>উচ্চারণ:</strong> রব্বানা- যালামনা- আনফুসানা- ওয়া ইল্লাম তাগফির লানা- ওয়া তারহামনা- লানাকূনান্না মিনাল খা-সিরীন।
                    </div>
                    <div class="small text-muted font-bangla mb-3">
                        <strong>অর্থ:</strong> হে আমাদের প্রতিপালক! আমরা নিজেদের ওপর জুলুম করেছি। আপনি যদি আমাদেরকে ক্ষমা না করেন এবং আমাদের ওপর দয়া না করেন, তবে আমরা অবশ্যই ক্ষতিগ্রস্তদের অন্তর্ভুক্ত হয়ে যাব।
                    </div>
                    <button class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="copyDuaText(this, 'رَبَّنَا ظَلَمْنَا أَنفُسَنَا وَإِن لَّمْ تَغْفِرْ لَنَا...\n\nঅর্থ: হে আমাদের প্রতিপালক! আমরা নিজেদের ওপর অবিচার করেছি। [সূরা আরাফ ৭:২৩]')">
                        <i class="fa-regular fa-copy me-1"></i> কপি করুন
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 3: Duas for Parents -->
    <div class="mb-5" id="parents">
        <div class="d-flex align-items-center gap-2 mb-4 pb-2 border-bottom" style="border-color: var(--border-color) !important;">
            <i class="fa-solid fa-people-roof text-info fs-4"></i>
            <h2 class="h4 fw-bold font-bangla text-primary mb-0">পিতা-মাতার জন্য কুরআনী দোয়া</h2>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="h-100 p-4 rounded-4" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-info-subtle text-info rounded-pill font-bangla">মাতা-পিতার ওপর রহমতের দোয়া</span>
                        <span class="small text-muted font-bangla">সূরা আল-ইসরা ১৭:২৪</span>
                    </div>
                    <div class="font-amiri fs-3 text-end text-emerald mb-3" dir="rtl">
                        رَّبِّ ارْحَمْهُمَا كَمَا رَبَّيَانِي صَغِيرًا
                    </div>
                    <div class="small mb-2 font-bangla text-primary">
                        <strong>উচ্চারণ:</strong> রব্বির হামহুমা- কামা- রব্বায়া-নী সগীরা-।
                    </div>
                    <div class="small text-muted font-bangla mb-3">
                        <strong>অর্থ:</strong> হে আমার প্রতিপালক! তাদের উভয়ের প্রতি দয়া করুন, যেমন তারা আমাকে শৈশবে লালন-পালন করেছেন।
                    </div>
                    <button class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="copyDuaText(this, 'رَّبِّ ارْحَمْهُمَا كَمَا رَبَّيَانِي صَغِيرًا\n\nঅর্থ: হে আমার প্রতিপালক! তাদের উভয়ের প্রতি দয়া করুন, যেমন তারা আমাকে শৈশবে স্নেহভরে লালন-পালন করেছেন। [সূরা বনী ইসরাঈল ১৭:২৪]')">
                        <i class="fa-regular fa-copy me-1"></i> কপি করুন
                    </button>
                </div>
            </div>

            <div class="col-md-6">
                <div class="h-100 p-4 rounded-4" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-info-subtle text-info rounded-pill font-bangla">ইবরাহিম (আ.)-এর পিতা-মাতার জন্য দোয়া</span>
                        <span class="small text-muted font-bangla">সূরা ইব্রাহিম ১৪:৪১</span>
                    </div>
                    <div class="font-amiri fs-4 text-end text-emerald mb-3" dir="rtl">
                        رَبَّنَا اغْفِرْ لِي وَلِوَالِدَيَّ وَلِلْمُؤْمِنِينَ يَوْمَ يَقُومُ الْحِسَابُ
                    </div>
                    <div class="small mb-2 font-bangla text-primary">
                        <strong>উচ্চারণ:</strong> রব্বানাগ ফির লী ওয়ালিওয়া-লিদাইয়্যা ওয়ালিলমু'মিনীনা ইয়াওমা ইয়াকূমুল হিসা-ব।
                    </div>
                    <div class="small text-muted font-bangla mb-3">
                        <strong>অর্থ:</strong> হে আমাদের প্রতিপালক! যেদিন হিসাব কায়েম হবে, সেদিন আমাকে, আমার পিতা-মাতাকে এবং সমস্ত মুমিনকে ক্ষমা করে দিন।
                    </div>
                    <button class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="copyDuaText(this, 'رَبَّنَا اغْفِرْ لِي وَلِوَالِدَيَّ وَلِلْمُؤْمِنِينَ يَوْمَ يَقُومُ الْحِسَابُ\n\nঅর্থ: হে আমাদের পালনকর্তা! যেদিন হিসাব অনুষ্ঠিত হবে, সেদিন আমাকে, আমার পিতা-মাতাকে এবং মুমিনদেরকে ক্ষমা করুন। [সূরা ইব্রাহিম ১৪:৪১]')">
                        <i class="fa-regular fa-copy me-1"></i> কপি করুন
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="mb-5">
        <h2 class="h4 fw-bold font-bangla text-center text-primary mb-4">দোয়া সম্পর্কিত সাধারণ জিজ্ঞাসা (FAQ)</h2>
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
