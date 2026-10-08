<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        User::updateOrCreate(
            ['email' => 'saad@quran.com'],
            [
                'name' => 'Engr Saad',
                'password' => Hash::make('mDrasel477!@#'),
                'must_change_password' => false,
            ]
        );

        // Initial Seed Notifications for testing
        Notification::updateOrCreate(
            ['title_en' => 'Welcome to Al Quran App'],
            [
                'title_bn' => 'আল কুরআনে আপনাকে স্বাগতম',
                'short_description_en' => 'Read and listen to the Holy Quran with English and Bengali translations.',
                'short_description_bn' => 'ইংরেজি ও বাংলা অনুবাদ সহ পবিত্র কুরআন তিলাওয়াত ও শ্রবণ করুন।',
                'content_en' => '<p>Assalamu Alaikum. Welcome to the official Al Quran application. Access full Quranic verses, word-by-word translations, audio recitations by famous Qaris, prayer timing, Qibla compass, and daily Islamic reminders.</p>',
                'content_bn' => '<p>আসসালামু আলাইকুম। আল কুরআন অ্যাপ্লিকেশনে আপনাকে স্বাগতম। সম্পূর্ণ কুরআন মাজীদের আয়াত, শব্দে শব্দে অর্থ, প্রখ্যাত ক্বারীদের তিলাওয়াত, নামাজের সময়সূচী, কিবলা কম্পাস এবং দৈনিক ইসলামিক রিমাইন্ডার পান।</p>',
                'image' => null,
                'banner_image' => null,
                'notification_type' => 'announcements',
                'is_active' => true,
                'publish_date' => now(),
            ]
        );

        Notification::updateOrCreate(
            ['title_en' => 'Beautiful Hadith of the Day'],
            [
                'title_bn' => 'সুন্দর একটি হাদীস দিয়ে নতুন দিন শুরু',
                'short_description_en' => 'The best among you are those who learn the Quran and teach it.',
                'short_description_bn' => 'তোমাদের মধ্যে সর্বশ্রেষ্ঠ ব্যক্তি সেই যে কুরআন শিক্ষা করে এবং অন্যকে শিক্ষা দেয়।',
                'content_en' => '<p>Prophet Muhammad (ﷺ) said: <strong>"The best of you are those who learn the Quran and teach it."</strong> (Sahih Al-Bukhari 5027).</p><p>Spend a few minutes daily reciting the Holy Quran to earn immense rewards from Allah Almighty.</p>',
                'content_bn' => '<p>রাসূলুল্লাহ (ﷺ) বলেছেন: <strong>"তোমাদের মধ্যে সর্বোত্তম সেই ব্যক্তি যে কুরআন শেখে এবং অন্যকে শিক্ষা দেয়।"</strong> (সহীহ বুখারী ৫০২৭)।</p><p>আল্লাহ তায়ালার অশেষ সওয়াব অর্জনের জন্য প্রতিদিন অন্তত কিছু সময় কুরআন তিলাওয়াত করুন।</p>',
                'image' => null,
                'banner_image' => null,
                'notification_type' => 'daily_hadith',
                'is_active' => true,
                'publish_date' => now()->subDay(),
            ]
        );
    }
}
