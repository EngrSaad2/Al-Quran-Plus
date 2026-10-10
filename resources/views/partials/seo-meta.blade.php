@php
    $pageTitle = $metaTitle ?? 'Al Quran Plus - Read & Listen Holy Quran Online';
    $pageDesc = $metaDesc ?? 'Read and listen to the Holy Quran online on Al Quran Plus with authentic Bangla and English translations, crystal-clear Arabic text, and 114 Surahs audio.';
    $pageKeywords = $metaKeywords ?? 'Al Quran, Holy Quran Online, Quran English Translation, Quran Bangla Translation, Quran Audio Recitation, Read Quran Online, Surah Yaseen, Surah Rahman, Quran Tafsir, Islamic Dua, Al Quran Plus';
    $pageCanonical = $canonicalUrl ?? url()->current();
    $pageOgImage = $ogImage ?? asset('images/seo-og-banner.png');
    $pageType = $ogType ?? 'website';
@endphp

<title>{{ $pageTitle }}</title>
<meta name="description" content="{{ $pageDesc }}">
<meta name="keywords" content="{{ $pageKeywords }}">
<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">

<!-- Canonical & Language Alternates -->
<link rel="canonical" href="{{ $pageCanonical }}">
<link rel="alternate" hreflang="en" href="{{ $pageCanonical }}?lang=en">
<link rel="alternate" hreflang="bn" href="{{ $pageCanonical }}?lang=bn">
<link rel="alternate" hreflang="x-default" href="{{ $pageCanonical }}">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="{{ $pageType }}">
<meta property="og:url" content="{{ $pageCanonical }}">
<meta property="og:title" content="{{ $pageTitle }}">
<meta property="og:description" content="{{ $pageDesc }}">
<meta property="og:image" content="{{ $pageOgImage }}">
<meta property="og:image:secure_url" content="{{ $pageOgImage }}">
<meta property="og:image:type" content="image/png">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="Al Quran Plus — Read and Listen to Holy Quran Online">
<meta property="og:site_name" content="Al Quran Plus">
<meta property="og:locale" content="en_US">
<meta property="og:locale:alternate" content="bn_BD">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ $pageCanonical }}">
<meta name="twitter:title" content="{{ $pageTitle }}">
<meta name="twitter:description" content="{{ $pageDesc }}">
<meta name="twitter:image" content="{{ $pageOgImage }}">
<meta name="twitter:image:alt" content="Al Quran Plus — Read and Listen to Holy Quran Online">

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
