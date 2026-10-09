@php
    $pageTitle = $metaTitle ?? 'আল কুরআন বাংলা ও ইংরেজি অনুবাদ | কুরআন মাজিদ অনলাইন - Al Quran Plus';
    $pageDesc = $metaDesc ?? 'পবিত্র আল কুরআন পড়ুন ও শুনুন বিশুদ্ধ আরবি, সহজ বাংলা ও ইংরেজি অনুবাদসহ। ১১৪টি সূরার অডিও তিলাওয়াত, তাফসীর, ইসলামিক দোয়া ও নামাজের সময়সূচি।';
    $pageKeywords = $metaKeywords ?? 'আল কুরআন বাংলা, কুরআন শরীফ, Quran Bangla Translation, Quran with English, Quran Tafsir Bangla, সূরা ইয়াসিন, সূরা আর রহমান, ইসলামিক দোয়া, নামাজের সময়সূচি বাংলাদেশ';
    $pageCanonical = $canonicalUrl ?? url()->current();
    $pageOgImage = $ogImage ?? asset('favicon.png');
    $pageType = $ogType ?? 'website';
@endphp

<title>{{ $pageTitle }}</title>
<meta name="description" content="{{ $pageDesc }}">
<meta name="keywords" content="{{ $pageKeywords }}">
<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">

<!-- Canonical & Language Alternates -->
<link rel="canonical" href="{{ $pageCanonical }}">
<link rel="alternate" hreflang="bn-BD" href="{{ $pageCanonical }}?lang=bn">
<link rel="alternate" hreflang="en" href="{{ $pageCanonical }}?lang=en">
<link rel="alternate" hreflang="x-default" href="{{ $pageCanonical }}">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="{{ $pageType }}">
<meta property="og:url" content="{{ $pageCanonical }}">
<meta property="og:title" content="{{ $pageTitle }}">
<meta property="og:description" content="{{ $pageDesc }}">
<meta property="og:image" content="{{ $pageOgImage }}">
<meta property="og:site_name" content="Al Quran Plus">
<meta property="og:locale" content="bn_BD">
<meta property="og:locale:alternate" content="en_US">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ $pageCanonical }}">
<meta name="twitter:title" content="{{ $pageTitle }}">
<meta name="twitter:description" content="{{ $pageDesc }}">
<meta name="twitter:image" content="{{ $pageOgImage }}">

<!-- Structured Data (JSON-LD) -->
@if(isset($websiteSchema))
<script type="application/ld+json">
{!! json_encode($websiteSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endif

@if(isset($orgSchema))
<script type="application/ld+json">
{!! json_encode($orgSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endif

@if(isset($breadcrumbSchema))
<script type="application/ld+json">
{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endif

@if(isset($surahSchema))
<script type="application/ld+json">
{!! json_encode($surahSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endif

@if(isset($faqSchema))
<script type="application/ld+json">
{!! json_encode($faqSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endif
