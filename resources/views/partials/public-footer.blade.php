<!-- Comprehensive High-Authority SEO Footer -->
<footer class="site-public-footer" style="background: var(--bg-card); border-top: 1px solid var(--border-color); padding: 50px 20px 30px; margin-top: 60px;">
    <div class="container" style="max-width: 1200px; margin: 0 auto;">
        <div class="row g-4 mb-4">
            <!-- Brand & Mission Column -->
            <div class="col-lg-4 col-md-6">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <img src="{{ asset('favicon.png') }}" alt="Al Quran Plus" style="width: 32px; height: 32px; border-radius: 8px;">
                    <span class="font-bangla fw-bold fs-5" style="color: var(--text-primary);">আল কুরআন প্লাস</span>
                </div>
                <p class="small mb-3" style="color: var(--text-muted); line-height: 1.7;">
                    পবিত্র কুরআনুল কারীমের বিশুদ্ধ আরবি তিলাওয়াত, সহজ-সরল বাংলা ও ইংরেজি অনুবাদ, বিস্তারিত তাফসীর, ইসলামিক দোয়া এবং নামাজের সময়সূচি অধ্যয়নের একটি পূর্ণাঙ্গ, বিজ্ঞাপনহীন উন্মুক্ত ইসলামিক প্ল্যাটফর্ম।
                </p>
                <div class="d-flex align-items-center gap-2 small" style="color: var(--primary-color);">
                    <i class="fa-solid fa-shield-halved"></i>
                    <span>নির্ভরযোগ্য ও বিশুদ্ধ ইসলামিক তথ্যসূত্র</span>
                </div>
            </div>

            <!-- Quran & Translations -->
            <div class="col-lg-3 col-md-6">
                <h6 class="font-bangla fw-bold mb-3" style="color: var(--text-primary); font-size: 0.95rem;">
                    <i class="fa-solid fa-book-quran me-1 text-emerald"></i> কুরআন ও অনুবাদ
                </h6>
                <ul class="list-unstyled d-flex flex-column gap-2 small" style="color: var(--text-muted);">
                    <li><a href="{{ route('public.quran.bangla') }}" class="text-decoration-none" style="color: var(--text-muted);">বাংলা অনুবাদসহ কুরআন (১১৪ সূরা)</a></li>
                    <li><a href="{{ route('public.quran.english') }}" class="text-decoration-none" style="color: var(--text-muted);">Quran English Translation (Sahih)</a></li>
                    <li><a href="{{ route('public.quran.tafsir') }}" class="text-decoration-none" style="color: var(--text-muted);">কুরআনের তাফসীর বাংলা</a></li>
                    <li><a href="{{ route('public.quran.audio') }}" class="text-decoration-none" style="color: var(--text-muted);">কুরআন অডিও তিলাওয়াত (৮ ক্বারী)</a></li>
                    <li><a href="{{ route('public.search') }}" class="text-decoration-none" style="color: var(--text-muted);">কুরআন অনুসন্ধান (বাংলা ও আরবি)</a></li>
                </ul>
            </div>

            <!-- Popular Surahs -->
            <div class="col-lg-2 col-md-6">
                <h6 class="font-bangla fw-bold mb-3" style="color: var(--text-primary); font-size: 0.95rem;">
                    <i class="fa-solid fa-star me-1 text-warning"></i> ফজিলতপূর্ণ সূরা
                </h6>
                <ul class="list-unstyled d-flex flex-column gap-2 small" style="color: var(--text-muted);">
                    <li><a href="{{ route('public.surah', ['id' => 'yaseen']) }}" class="text-decoration-none" style="color: var(--text-muted);">সূরা ইয়াসিন (Surah Yaseen)</a></li>
                    <li><a href="{{ route('public.surah', ['id' => 'rahman']) }}" class="text-decoration-none" style="color: var(--text-muted);">সূরা আর-রহমান (Surah Rahman)</a></li>
                    <li><a href="{{ route('public.surah', ['id' => 'mulk']) }}" class="text-decoration-none" style="color: var(--text-muted);">সূরা আল-মুলক (Surah Mulk)</a></li>
                    <li><a href="{{ route('public.surah', ['id' => 'kahf']) }}" class="text-decoration-none" style="color: var(--text-muted);">সূরা আল-কাহফ (Surah Kahf)</a></li>
                    <li><a href="{{ route('public.surah', ['id' => 'fatiha']) }}" class="text-decoration-none" style="color: var(--text-muted);">সূরা আল-ফাতিহা (Surah Fatiha)</a></li>
                    <li><a href="{{ route('public.surah', ['id' => 56]) }}" class="text-decoration-none" style="color: var(--text-muted);">সূরা আল-ওয়াকিয়াহ (Waqi'ah)</a></li>
                </ul>
            </div>

            <!-- Islamic Tools & Info -->
            <div class="col-lg-3 col-md-6">
                <h6 class="font-bangla fw-bold mb-3" style="color: var(--text-primary); font-size: 0.95rem;">
                    <i class="fa-solid fa-mosque me-1 text-emerald"></i> ইসলামিক আমল ও সেবা
                </h6>
                <ul class="list-unstyled d-flex flex-column gap-2 small" style="color: var(--text-muted);">
                    <li><a href="{{ route('public.dua') }}" class="text-decoration-none" style="color: var(--text-muted);">প্রয়োজনীয় ইসলামিক দোয়া ও মোনাজাত</a></li>
                    <li><a href="{{ route('public.prayer-times') }}" class="text-decoration-none" style="color: var(--text-muted);">নামাজের সময়সূচি বাংলাদেশ (৬৪ জেলা)</a></li>
                    <li><a href="{{ route('public.daily-ayah') }}" class="text-decoration-none" style="color: var(--text-muted);">প্রতিদিনের কুরআনের আয়াত ও উপদেশ</a></li>
                    <li><a href="{{ route('public.bookmarks') }}" class="text-decoration-none" style="color: var(--text-muted);">সংরক্ষিত আয়াত ও বুকমার্ক</a></li>
                    <li><a href="{{ route('public.about') }}" class="text-decoration-none" style="color: var(--text-muted);">অ্যাপ সম্পর্কে</a></li>
                    <li><a href="{{ route('public.privacy') }}" class="text-decoration-none" style="color: var(--text-muted);">গোপনীয়তা নীতি (Privacy Policy)</a></li>
                    <li><a href="{{ route('public.contact') }}" class="text-decoration-none" style="color: var(--text-muted);">যোগাযোগ ও ফিডব্যাক</a></li>
                    <li><a href="{{ url('/sitemap.xml') }}" class="text-decoration-none" style="color: var(--text-muted);">এক্সএমএল সাইটম্যাপ (XML Sitemap)</a></li>
                </ul>
            </div>
        </div>

        <hr style="border-color: var(--border-color); margin: 25px 0 20px;">

        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-2 small" style="color: var(--text-dim);">
            <div>
                &copy; {{ date('Y') }} <strong>Al Quran Plus</strong>. পবিত্র কুরআন পাঠ ও গবেষণার জন্য একটি অলাভজনক ইসলামিক উদ্যোগ।
            </div>
            <div class="d-flex gap-3">
                <a href="{{ route('public.quran.bangla') }}" class="text-decoration-none" style="color: var(--text-dim);">বাংলা</a>
                <span>•</span>
                <a href="{{ route('public.quran.english') }}" class="text-decoration-none" style="color: var(--text-dim);">English</a>
                <span>•</span>
                <a href="{{ route('public.privacy') }}" class="text-decoration-none" style="color: var(--text-dim);">Privacy</a>
                <span>•</span>
                <a href="{{ route('public.contact') }}" class="text-decoration-none" style="color: var(--text-dim);">Contact</a>
            </div>
        </div>
    </div>
</footer>
