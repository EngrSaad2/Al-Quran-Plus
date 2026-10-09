<?php

namespace App\Services;

class SeoService
{
    public static function getWebSiteSchema(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => 'Al Quran Plus',
            'alternateName' => ['কুরআন মাজিদ', 'Quran Mazid', 'Al Quran Online Bangladesh'],
            'url' => url('/'),
            'description' => 'পবিত্র কুরআনুল কারীমের বিশুদ্ধ আরবি তিলাওয়াত, সহজ-সরল বাংলা ও ইংরেজি অনুবাদ এবং ইসলামিক রিসোর্স।',
            'inLanguage' => ['bn-BD', 'en', 'ar'],
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => url('/search') . '?q={search_term_string}'
                ],
                'query-input' => 'required name=search_term_string'
            ]
        ];
    }

    public static function getOrganizationSchema(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'Al Quran Plus',
            'url' => url('/'),
            'logo' => [
                '@type' => 'ImageObject',
                'url' => asset('favicon.png'),
                'width' => 192,
                'height' => 192
            ]
        ];
    }

    public static function getBreadcrumbSchema(array $items): array
    {
        $itemList = [];
        $pos = 1;
        foreach ($items as $name => $itemUrl) {
            $itemList[] = [
                '@type' => 'ListItem',
                'position' => $pos++,
                'name' => $name,
                'item' => $itemUrl
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $itemList
        ];
    }

    public static function getSurahSchema(array $surah): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'ItemPage',
            'name' => "সূরা {$surah['bangla']} (Surah {$surah['name']})",
            'headline' => "পবিত্র কুরআনের সূরা {$surah['bangla']} - আরবি, বাংলা অনুবাদ ও অডিও তিলাওয়াত",
            'description' => "সূরা {$surah['bangla']} (Surah {$surah['name']}), কুরআনের {$surah['id']} নম্বর সূরা। মোট {$surah['verses']} আয়াত, {$surah['type']} সূরা। সহজ বাংলা অর্থ ও সুরমধুর অডিও।",
            'url' => url('/surah/' . $surah['id']),
            'inLanguage' => ['ar', 'bn-BD', 'en'],
            'mainEntity' => [
                '@type' => 'Chapter',
                'name' => $surah['name'],
                'alternateName' => [$surah['bangla'], $surah['arabic']],
                'position' => $surah['id'],
                'pagination' => (string)$surah['verses'],
                'inLanguage' => 'ar'
            ]
        ];
    }

    public static function getFaqSchema(array $faqs): array
    {
        $mainEntity = [];
        foreach ($faqs as $q => $a) {
            $mainEntity[] = [
                '@type' => 'Question',
                'name' => $q,
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $a
                ]
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $mainEntity
        ];
    }
}
