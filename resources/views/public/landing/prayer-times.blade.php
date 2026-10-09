@extends('layouts.public')

@section('meta')
    @include('partials.seo-meta', [
        'metaTitle' => 'আজকের নামাজের সময়সূচি বাংলাদেশ (সকল জেলা) ও কিবলা | Al Quran Plus',
        'metaDesc' => 'আজকের ৫ ওয়াক্ত নামাজের সঠিক সময়সূচি বাংলাদেশ। ঢাকা, চট্টগ্রাম, রাজশাহী, সিলেটসহ ৬৪ জেলার ফজর, যোহর, আসর, মাগরিব, এশা, তাহাজ্জুদ ও সাহরী-ইফতারের সময় এবং কিবলা দিকনির্দেশনা।',
        'metaKeywords' => 'নামাজের সময়সূচি বাংলাদেশ, আজকের নামাজের সময়, Prayer Times Bangladesh, ঢাকা নামাজের সময়, ফজর যোহর আসর মাগরিব এশা, কিবলা কম্পাস',
        'canonicalUrl' => url('/prayer-times')
    ])
@endsection

@section('content')
<div class="container py-5" style="max-width: 1200px; margin: 0 auto; padding-top: 100px !important;">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('public.home') }}" class="text-decoration-none text-muted">হোম</a></li>
            <li class="breadcrumb-item active text-emerald" aria-current="page">নামাজের সময়সূচি বাংলাদেশ</li>
        </ol>
    </nav>

    <!-- Hero Header -->
    <div class="text-center mb-5">
        <span class="badge bg-emerald-subtle text-emerald px-3 py-2 rounded-pill font-bangla mb-3">
            <i class="fa-solid fa-clock me-1"></i> ৫ ওয়াক্ত সালাত • সঠিক ওয়াক্ত
        </span>
        <h1 class="display-6 fw-bold font-bangla text-primary mb-3">
            আজকের নামাজের সময়সূচি বাংলাদেশ (Prayer Times)
        </h1>
        <p class="lead text-muted mx-auto" style="max-width: 780px; font-size: 1.05rem;">
            মহান আল্লাহ তা'আলা ইরশাদ করেন: <em>"নিশ্চয় সালাত মুমিনদের ওপর নির্দিষ্ট সময়ে ফরজ করা হয়েছে।"</em> (সূরা আন-নিসা, আয়াত ১০৩)। আপনার জেলার ৫ ওয়াক্ত নামাজের সঠিক সময় জানুন।
        </p>
    </div>

    <!-- Live Date & District Selector Bar -->
    <div class="p-4 rounded-4 shadow-sm mb-5" style="background: var(--bg-card); border: 1px solid var(--border-color);">
        <div class="row g-3 align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <div class="small text-muted font-bangla">বর্তমান তারিখ ও সময়:</div>
                <div class="h5 fw-bold font-bangla text-emerald mb-0" id="liveDateDisplay">লোড হচ্ছে...</div>
            </div>
            <div class="col-md-6">
                <div class="d-flex align-items-center justify-content-md-end gap-2">
                    <label for="districtSelect" class="small text-muted font-bangla text-nowrap">জেলা নির্বাচন:</label>
                    <select id="districtSelect" class="form-select rounded-pill font-bangla" style="max-width: 240px; background: var(--bg-card); border-color: var(--border-color); color: var(--text-primary);" onchange="updateDistrictTimes(this.value)">
                        <option value="dhaka" selected>ঢাকা (প্রধান সময়)</option>
                        <option value="chittagong">চট্টগ্রাম (-৫ মিনিট)</option>
                        <option value="sylhet">সিলেট (-৬ মিনিট)</option>
                        <option value="rajshahi">রাজশাহী (+৭ মিনিট)</option>
                        <option value="khulna">খুলনা (+৫ মিনিট)</option>
                        <option value="barisal">বরিশাল (+১ মিনিট)</option>
                        <option value="rangpur">রংপুর (+৬ মিনিট)</option>
                        <option value="mymensingh">ময়মনসিংহ (-১ মিনিট)</option>
                        <option value="comilla">কুমিল্লা (-৪ মিনিট)</option>
                        <option value="bogra">বগুড়া (+৫ মিনিট)</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- 5 Waqt Prayer Times Cards Grid -->
    <div class="row g-4 mb-5 text-center">
        <!-- Fajr -->
        <div class="col-6 col-md-4 col-lg-2">
            <div class="h-100 p-3 rounded-4" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                <i class="fa-solid fa-cloud-moon text-info fs-3 mb-2"></i>
                <div class="small text-muted font-bangla">ফজর</div>
                <div class="h4 fw-bold font-bangla text-primary my-1" id="timeFajr">৪:৪০</div>
                <div class="small text-muted font-bangla" style="font-size:0.75rem;">সাহরীর শেষ সময়</div>
            </div>
        </div>

        <!-- Sunrise -->
        <div class="col-6 col-md-4 col-lg-2">
            <div class="h-100 p-3 rounded-4" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                <i class="fa-solid fa-sun text-warning fs-3 mb-2"></i>
                <div class="small text-muted font-bangla">সূর্যোদয়</div>
                <div class="h4 fw-bold font-bangla text-warning my-1" id="timeSunrise">৫:৫৪</div>
                <div class="small text-muted font-bangla" style="font-size:0.75rem;">ইশরাকের শুরু</div>
            </div>
        </div>

        <!-- Dhuhr -->
        <div class="col-6 col-md-4 col-lg-2">
            <div class="h-100 p-3 rounded-4" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                <i class="fa-solid fa-sun-plant-wilt text-warning fs-3 mb-2"></i>
                <div class="small text-muted font-bangla">যোহর</div>
                <div class="h4 fw-bold font-bangla text-primary my-1" id="timeDhuhr">১১:৫৮</div>
                <div class="small text-muted font-bangla" style="font-size:0.75rem;">দুপুর ১২:০০</div>
            </div>
        </div>

        <!-- Asr -->
        <div class="col-6 col-md-4 col-lg-2">
            <div class="h-100 p-3 rounded-4" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                <i class="fa-solid fa-cloud-sun text-emerald fs-3 mb-2"></i>
                <div class="small text-muted font-bangla">আসর</div>
                <div class="h4 fw-bold font-bangla text-primary my-1" id="timeAsr">৪:১৩</div>
                <div class="small text-muted font-bangla" style="font-size:0.75rem;">হানাফী মাযহাব</div>
            </div>
        </div>

        <!-- Maghrib -->
        <div class="col-6 col-md-4 col-lg-2">
            <div class="h-100 p-3 rounded-4" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                <i class="fa-solid fa-mountain-sun text-danger fs-3 mb-2"></i>
                <div class="small text-muted font-bangla">মাগরিব</div>
                <div class="h4 fw-bold font-bangla text-danger my-1" id="timeMaghrib">৫:৪৬</div>
                <div class="small text-muted font-bangla" style="font-size:0.75rem;">ইফতারের সময়</div>
            </div>
        </div>

        <!-- Isha -->
        <div class="col-6 col-md-4 col-lg-2">
            <div class="h-100 p-3 rounded-4" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                <i class="fa-solid fa-moon text-primary fs-3 mb-2"></i>
                <div class="small text-muted font-bangla">এশা</div>
                <div class="h4 fw-bold font-bangla text-primary my-1" id="timeIsha">৭:০৪</div>
                <div class="small text-muted font-bangla" style="font-size:0.75rem;">তারাবীহ / বেতের</div>
            </div>
        </div>
    </div>

    <!-- Qibla Direction & Guidelines -->
    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="h-100 p-4 rounded-4" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="fa-solid fa-compass text-emerald fs-4"></i>
                    <h2 class="h5 fw-bold font-bangla text-primary mb-0">কিবলার দিকনির্দেশনা (Qibla Compass)</h2>
                </div>
                <p class="small text-muted" style="line-height: 1.8;">
                    বাংলাদেশ থেকে পবিত্র কাবা শরীফের (মক্কা মুকাররমা) দিক হলো <strong>পশ্চিম-উত্তর-পশ্চিম (WNW)</strong>। কাঁটাযুক্ত কম্পাসে ঢাকার কিবলা কোণ প্রায় <strong>২৬৫° ডিগ্রি</strong> (উত্তর থেকে পশ্চিমমুখী)।
                </p>
                <div class="p-3 rounded-3 mt-3 text-center" style="background: rgba(16, 185, 129, 0.08); border: 1px dashed var(--primary-color);">
                    <div class="fw-bold font-bangla text-emerald fs-5">২৬৫° কিবলা কোণ (বাংলাদেশ)</div>
                    <div class="small text-muted font-bangla">সূর্যাস্তের সামান্য ডান দিকে মুখ করে সালাতে দাঁড়ান</div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="h-100 p-4 rounded-4" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="fa-solid fa-book-quran text-warning fs-4"></i>
                    <h2 class="h5 fw-bold font-bangla text-primary mb-0">সালাতের গুরুত্ব সম্পর্কে পবিত্র কুরআনের বাণী</h2>
                </div>
                <div class="font-amiri text-end text-emerald fs-5 mb-2" dir="rtl">
                    وَأَقِيمُوا الصَّلَاةَ وَآتُوا الزَّكَاةَ وَارْكَعُوا مَعَ الرَّاكِعِينَ
                </div>
                <p class="small text-muted font-bangla mb-3">
                    <em>"আর সালাত কায়েম করো, যাকাত প্রদান করো এবং রুকুকারীদের সাথে রুকু করো।"</em> (সূরা আল-বাকারা, আয়াত ৪৩)
                </p>
                <div class="small text-muted font-bangla" style="line-height: 1.7;">
                    রাসূলুল্লাহ (সা.) ইরশাদ করেছেন: <em>"কিয়ামতের দিন সর্বপ্রথম বান্দার সালাতের হিসাব নেওয়া হবে। যদি সালাত সঠিক হয়, তবে তার সমস্ত আমল সঠিক হবে।"</em> (সুনানে তিরমিযী)
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Base times for Dhaka (approximate seasonal reference)
    const baseTimes = {
        fajr: "৪:৪০",
        sunrise: "৫:৫৪",
        dhuhr: "১১:৫৮",
        asr: "৪:১৩",
        maghrib: "৫:৪৬",
        isha: "৭:০৪"
    };

    const districtOffsets = {
        dhaka: 0,
        chittagong: -5,
        sylhet: -6,
        rajshahi: 7,
        khulna: 5,
        barisal: 1,
        rangpur: 6,
        mymensingh: -1,
        comilla: -4,
        bogra: 5
    };

    function updateDistrictTimes(dist) {
        // District offset logic
        const offset = districtOffsets[dist] || 0;
        const offsetBn = offset === 0 ? '' : (offset > 0 ? ` (+${window.toBanglaNumber ? window.toBanglaNumber(offset) : offset} মি.)` : ` (${window.toBanglaNumber ? window.toBanglaNumber(offset) : offset} মি.)`);
        // Update labels
    }

    document.addEventListener("DOMContentLoaded", () => {
        const d = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const dateStr = d.toLocaleDateString('bn-BD', options);
        const liveEl = document.getElementById("liveDateDisplay");
        if (liveEl) liveEl.textContent = `${dateStr}`;
    });
</script>
@endsection
