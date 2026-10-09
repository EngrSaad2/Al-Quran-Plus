<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\QuranDataService;
use App\Services\SeoService;

class HomeController extends Controller
{
    public function index()
    {
        $surahs = QuranDataService::getAllSurahs();
        $websiteSchema = SeoService::getWebSiteSchema();
        $orgSchema = SeoService::getOrganizationSchema();

        return view('public.home', compact('surahs', 'websiteSchema', 'orgSchema'));
    }

    public function surah($id = 1)
    {
        // Handle friendly slugs like 'yaseen', 'rahman', 'mulk', 'kahf', 'fatiha'
        if (!is_numeric($id)) {
            $slugId = QuranDataService::getIdBySlug($id);
            if ($slugId) {
                $id = $slugId;
            }
        }

        $surahId = max(1, min(114, (int)$id));
        $surah = QuranDataService::getSurah($surahId);
        $allSurahs = QuranDataService::getAllSurahs();

        $breadcrumbSchema = SeoService::getBreadcrumbSchema([
            'হোম' => url('/'),
            'কুরআন' => url('/quran/bangla-translation'),
            "সূরা {$surah['bangla']}" => url('/surah/' . $surahId)
        ]);

        $surahSchema = SeoService::getSurahSchema($surah);

        return view('public.surah', compact('surahId', 'surah', 'allSurahs', 'breadcrumbSchema', 'surahSchema'));
    }

    public function banglaTranslation()
    {
        $surahs = QuranDataService::getAllSurahs();
        $breadcrumbSchema = SeoService::getBreadcrumbSchema([
            'হোম' => url('/'),
            'বাংলা অনুবাদসহ কুরআন' => url('/quran/bangla-translation')
        ]);

        $faqs = [
            'পবিত্র কুরআনের নির্ভরযোগ্য বাংলা অনুবাদ কোনটি?' => 'সাধারণত ইসলামিক ফাউন্ডেশন বাংলাদেশ, ড. মুজিবুর রহমান এবং মহিউদ্দীন খান অনূদিত বাংলা অনুবাদ ব্যাপকভাবে নির্ভরযোগ্য ও সমাদৃত।',
            'অনলাইনে কি সম্পূর্ণ ১১৪টি সূরার বাংলা অনুবাদ পড়া সম্ভব?' => 'হ্যাঁ, Al Quran Plus প্ল্যাটফর্মে সম্পূর্ণ ১১৪টি সূরার বিশুদ্ধ আরবি ও নির্ভুল বাংলা অনুবাদ বিনামূল্যে পড়া ও তিলাওয়াত শোনা যায়।',
            'কুরআনের সাথে কি অডিও তিলাওয়াত শোনা যাবে?' => 'হ্যাঁ, মিশারী রাশিদ আল-আফাসী, শায়খ সুদাইস, মাহের আল-মুয়াইকিলিসহ ৮ জন বিশ্বখ্যাত ক্বারীর কণ্ঠে পূর্ণ সূরা একটানা শোনা যায়।'
        ];
        $faqSchema = SeoService::getFaqSchema($faqs);

        return view('public.landing.bangla-translation', compact('surahs', 'breadcrumbSchema', 'faqSchema', 'faqs'));
    }

    public function englishTranslation()
    {
        $surahs = QuranDataService::getAllSurahs();
        $breadcrumbSchema = SeoService::getBreadcrumbSchema([
            'Home' => url('/'),
            'Quran with English Translation' => url('/quran/english-translation')
        ]);

        $faqs = [
            'Which English translation is used on Al Quran Plus?' => 'We utilize the widely acclaimed Sahih International English translation for its clarity, contemporary phrasing, and alignment with classical Islamic scholarship.',
            'Can I listen to audio recitation while reading in English?' => 'Yes, our platform provides synchronized full-surah audio recitation from renowned Qaris alongside English translation and Arabic script.'
        ];
        $faqSchema = SeoService::getFaqSchema($faqs);

        return view('public.landing.english-translation', compact('surahs', 'breadcrumbSchema', 'faqSchema', 'faqs'));
    }

    public function tafsirBangla()
    {
        $surahs = QuranDataService::getAllSurahs();
        $breadcrumbSchema = SeoService::getBreadcrumbSchema([
            'হোম' => url('/'),
            'কুরআনের তাফসীর বাংলা' => url('/quran/tafsir/bangla')
        ]);

        $faqs = [
            'তাফসীর কেন গুরুত্বপূর্ণ?' => 'কুরআনের বাণীর শানে নুযূল (নাযিলের প্রেক্ষাপট), ঐতিহাসিক পটভূমি, এবং গভীর বিধান ও প্রজ্ঞা অনুধাবনের জন্য তাফসীর অপরিহার্য।',
            'বাংলা ভাষায় কোন তাফসীরগুলো সবচেয়ে নির্ভরযোগ্য?' => 'তাফসীরে ইবনে কাসীর (অনুবাদ), মাআরিফুল কুরআন (মুফতী মুহাম্মদ শফী রহ.), এবং তাফহীমুল কুরআন বাংলা ভাষাভাষীদের মাঝে সর্বাধিক সমাদৃত।'
        ];
        $faqSchema = SeoService::getFaqSchema($faqs);

        return view('public.landing.tafsir', compact('surahs', 'breadcrumbSchema', 'faqSchema', 'faqs'));
    }

    public function audio()
    {
        $surahs = QuranDataService::getAllSurahs();
        $breadcrumbSchema = SeoService::getBreadcrumbSchema([
            'হোম' => url('/'),
            'কুরআন অডিও ও তিলাওয়াত' => url('/quran/audio')
        ]);

        return view('public.landing.audio', compact('surahs', 'breadcrumbSchema'));
    }

    public function dua()
    {
        $breadcrumbSchema = SeoService::getBreadcrumbSchema([
            'হোম' => url('/'),
            'ইসলামিক দোয়া ও মোনাজাত' => url('/dua')
        ]);

        $faqs = [
            'রাব্বানা দোয়া কি?' => 'পবিত্র কুরআনে যে দোয়াগুলো "রাব্বানা" (হে আমাদের প্রতিপালক) শব্দ দিয়ে শুরু হয়েছে, সেগুলোকে রাব্বানা দোয়া বলা হয়। কুরআনে মোট ৪০টি অত্যন্ত ফজিলতপূর্ণ রাব্বানা দোয়া রয়েছে।',
            'দোয়া কবুলের বিশেষ সময়গুলো কি কি?' => 'ফরজ নামাজের পর, শেষ রাতের তাহাজ্জুদে, সিজদারত অবস্থায়, আজান ও ইকামতের মধ্যবর্তী সময়ে এবং জুমার দিনে আসরের পর দোয়া কবুল হওয়ার বিশেষ সম্ভাবনা থাকে।'
        ];
        $faqSchema = SeoService::getFaqSchema($faqs);

        return view('public.landing.dua', compact('breadcrumbSchema', 'faqSchema', 'faqs'));
    }

    public function prayerTimes()
    {
        $breadcrumbSchema = SeoService::getBreadcrumbSchema([
            'হোম' => url('/'),
            'নামাজের সময়সূচি বাংলাদেশ' => url('/prayer-times')
        ]);

        return view('public.landing.prayer-times', compact('breadcrumbSchema'));
    }

    public function dailyAyah()
    {
        $surahs = QuranDataService::getAllSurahs();
        $breadcrumbSchema = SeoService::getBreadcrumbSchema([
            'হোম' => url('/'),
            'প্রতিদিনের কুরআনের আয়াত' => url('/daily-ayah')
        ]);

        return view('public.landing.daily-ayah', compact('surahs', 'breadcrumbSchema'));
    }

    public function bookmarks()
    {
        return view('public.bookmarks');
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');
        return view('public.search', compact('query'));
    }

    public function juz($id = 1)
    {
        $juzId = max(1, min(30, (int)$id));
        return view('public.surah', ['surahId' => 1, 'juzId' => $juzId]);
    }

    public function about()
    {
        return view('public.about');
    }

    public function privacy()
    {
        return view('public.privacy');
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        return back()->with('success', 'Thank you for reaching out! We have received your message and will respond shortly.');
    }

    public function sitemap()
    {
        $surahs = QuranDataService::getAllSurahs();
        $lastmod = date('Y-m-d');

        $staticPages = [
            ['url' => url('/'), 'priority' => '1.0', 'changefreq' => 'daily'],
            ['url' => url('/quran/bangla-translation'), 'priority' => '0.95', 'changefreq' => 'weekly'],
            ['url' => url('/quran/english-translation'), 'priority' => '0.90', 'changefreq' => 'weekly'],
            ['url' => url('/quran/tafsir/bangla'), 'priority' => '0.90', 'changefreq' => 'weekly'],
            ['url' => url('/quran/audio'), 'priority' => '0.90', 'changefreq' => 'weekly'],
            ['url' => url('/dua'), 'priority' => '0.90', 'changefreq' => 'weekly'],
            ['url' => url('/prayer-times'), 'priority' => '0.90', 'changefreq' => 'daily'],
            ['url' => url('/daily-ayah'), 'priority' => '0.85', 'changefreq' => 'daily'],
            ['url' => url('/surah/yaseen'), 'priority' => '0.95', 'changefreq' => 'weekly'],
            ['url' => url('/surah/rahman'), 'priority' => '0.95', 'changefreq' => 'weekly'],
            ['url' => url('/surah/mulk'), 'priority' => '0.95', 'changefreq' => 'weekly'],
            ['url' => url('/surah/kahf'), 'priority' => '0.95', 'changefreq' => 'weekly'],
            ['url' => url('/surah/fatiha'), 'priority' => '0.95', 'changefreq' => 'weekly'],
            ['url' => url('/search'), 'priority' => '0.70', 'changefreq' => 'monthly'],
            ['url' => url('/bookmarks'), 'priority' => '0.60', 'changefreq' => 'monthly'],
            ['url' => url('/about'), 'priority' => '0.50', 'changefreq' => 'monthly'],
            ['url' => url('/privacy-policy'), 'priority' => '0.40', 'changefreq' => 'yearly'],
            ['url' => url('/contact'), 'priority' => '0.50', 'changefreq' => 'monthly'],
        ];

        return response()->view('public.sitemap', compact('surahs', 'staticPages', 'lastmod'))
                         ->header('Content-Type', 'application/xml; charset=utf-8')
                         ->header('Cache-Control', 'public, max-age=86400');
    }

    public function robots()
    {
        $content = "User-agent: *\n";
        $content .= "Allow: /\n";
        $content .= "Disallow: /admin/\n";
        $content .= "Disallow: /login\n\n";
        $content .= "Sitemap: " . url('/sitemap.xml') . "\n";

        return response($content, 200)
            ->header('Content-Type', 'text/plain');
    }
}
