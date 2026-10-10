@extends('layouts.public')

@section('meta')
    @include('partials.seo-meta', [
        'metaTitle' => 'Al Quran Plus - কুরআন অডিও তিলাওয়াত (৮ বিশ্বখ্যাত ক্বারী)',
        'metaDesc' => 'Al Quran Plus-এ পবিত্র কুরআনের ১১৪টি সূরার সুমধুর তিলাওয়াত শুনুন। মিশারী রাশিদ ও শায়খ সুদাইসসহ ৮ বিশ্বখ্যাত ক্বারীর কণ্ঠে সম্পূর্ণ অডিও তিলাওয়াত।',
        'metaKeywords' => 'কুরআন অডিও, Quran Audio MP3, মিশারী রাশিদ আফাসী, শায়খ সুদাইস, কুরআন তিলাওয়াত শুনুন, Surah Audio Recitation',
        'canonicalUrl' => url('/quran/audio')
    ])
@endsection

@section('content')
<div class="container py-5" style="max-width: 1200px; margin: 0 auto; padding-top: 100px !important;">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('public.home') }}" class="text-decoration-none text-muted">হোম</a></li>
            <li class="breadcrumb-item active text-emerald" aria-current="page">কুরআন অডিও তিলাওয়াত</li>
        </ol>
    </nav>

    <!-- Hero Header -->
    <div class="text-center mb-5">
        <span class="badge bg-emerald-subtle text-emerald px-3 py-2 rounded-pill font-bangla mb-3">
            <i class="fa-solid fa-headphones me-1"></i> বিশ্বমানের বিশুদ্ধ অডিও
        </span>
        <h1 class="display-6 fw-bold font-bangla text-primary mb-3">
            কুরআন অডিও তিলাওয়াত (Quran Audio Recitation)
        </h1>
        <p class="lead text-muted mx-auto" style="max-width: 780px; font-size: 1.05rem;">
            বিশ্ববিখ্যাত আন্তর্জাতিক ক্বারীদের কণ্ঠে সম্পূর্ণ ১১৪টি সূরার হৃদয়গ্রাহী ও সুরেলা তিলাওয়াত শুনুন। কোনো আয়াত বিরতি ছাড়া একটানা পূর্ণাঙ্গ সূরা স্টুডিও কোয়ালিটিতে উপভোগ করুন।
        </p>
    </div>

    <!-- Reciters Showcase Cards -->
    <div class="mb-5">
        <h2 class="h4 fw-bold font-bangla text-primary mb-4 text-center">আন্তর্জাতিক ক্বারীগণের তালিকা</h2>
        <div class="row g-4">
            <div class="col-md-3 col-6">
                <div class="p-4 rounded-4 text-center h-100" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center text-emerald fs-2" style="width: 60px; height: 60px; background: rgba(16, 185, 129, 0.1);">
                        <i class="fa-solid fa-microphone-lines"></i>
                    </div>
                    <h3 class="h6 fw-bold font-bangla mb-1">মিশারী রাশিদ আল-আফাসী</h3>
                    <div class="small text-muted font-amiri mb-2">مشاري راشد العفاسي</div>
                    <span class="badge bg-success-subtle text-success rounded-pill small">মুরাত্তাল • কুয়েত</span>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="p-4 rounded-4 text-center h-100" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center text-primary fs-2" style="width: 60px; height: 60px; background: rgba(59, 130, 246, 0.1);">
                        <i class="fa-solid fa-kaaba"></i>
                    </div>
                    <h3 class="h6 fw-bold font-bangla mb-1">আব্দুর রহমান আস-সুদাইস</h3>
                    <div class="small text-muted font-amiri mb-2">عبد الرحمن السديس</div>
                    <span class="badge bg-primary-subtle text-primary rounded-pill small">ইমাম • মসজিদুল হারাম</span>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="p-4 rounded-4 text-center h-100" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center text-warning fs-2" style="width: 60px; height: 60px; background: rgba(245, 158, 11, 0.1);">
                        <i class="fa-solid fa-mosque"></i>
                    </div>
                    <h3 class="h6 fw-bold font-bangla mb-1">মাহের আল-মুয়াইকিলি</h3>
                    <div class="small text-muted font-amiri mb-2">ماهر المعيقلي</div>
                    <span class="badge bg-warning-subtle text-dark rounded-pill small">ইমাম • মসজিদুল হারাম</span>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="p-4 rounded-4 text-center h-100" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center text-info fs-2" style="width: 60px; height: 60px; background: rgba(6, 182, 212, 0.1);">
                        <i class="fa-solid fa-star-and-crescent"></i>
                    </div>
                    <h3 class="h6 fw-bold font-bangla mb-1">মুহাম্মদ সিদ্দীক আল-মিনশাবী</h3>
                    <div class="small text-muted font-amiri mb-2">محمد صديق المنشاوي</div>
                    <span class="badge bg-info-subtle text-info rounded-pill small">ঐতিহাসিক ক্লাসিক • মিশর</span>
                </div>
            </div>
        </div>
    </div>

    <!-- 114 Surahs Audio Directory Grid -->
    <div class="mb-5">
        <h2 class="h4 fw-bold font-bangla text-primary mb-4 text-center">১১৪ সূরার যেকোনো অডিও শুনুন</h2>
        <div class="row g-3">
            @foreach($surahs as $s)
            <div class="col-6 col-md-4 col-lg-3">
                <a href="{{ route('public.surah', $s['id']) }}?autoplay=1" class="p-3 d-flex align-items-center justify-content-between rounded-3 text-decoration-none shadow-sm" style="background: var(--bg-card); border: 1px solid var(--border-color); color: var(--text-primary);">
                    <div>
                        <div class="small font-bangla fw-bold">{{ App\Services\QuranDataService::toBanglaNumber($s['id']) }}. সূরা {{ $s['bangla'] }}</div>
                        <div class="text-muted small" style="font-size:0.75rem;">{{ $s['name'] }}</div>
                    </div>
                    <div class="btn-icon-circle" style="width:32px; height:32px; font-size:0.75rem;">
                        <i class="fa-solid fa-play"></i>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
