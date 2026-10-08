@extends('layouts.public')

@section('title', 'About Us - Al Quran Application')

@section('content')
<section class="py-5">
    <div class="container py-4">
        <div class="max-w-800 mx-auto bg-dark p-4 p-md-5 rounded-4 border border-secondary">
            <h1 class="fw-bold text-emerald mb-4">About Al Quran Application</h1>
            <p class="lead text-light">Al Quran Application (com.engrsaad.quran) is a non-profit digital Islamic initiative designed to bring the Holy Quran closer to readers with precision, clarity, and beautiful design.</p>
            <hr class="border-emerald opacity-25 my-4">
            <h4 class="text-white fw-bold">Our Vision</h4>
            <p class="text-light-subtle">To deliver an authentic, high-quality, and modern Quranic experience accessible to English and Bengali speaking Muslims around the globe.</p>
            <h4 class="text-white fw-bold mt-4">Key Objectives</h4>
            <ul class="text-light-subtle d-flex flex-column gap-2">
                <li>Provide accurate verse-by-verse and word-by-word translations in Bengali and English.</li>
                <li>Ensure authentic right-to-left (RTL) Arabic typography and layout integrity.</li>
                <li>Deliver daily Hadith and Islamic reminders via Firebase Cloud Messaging.</li>
                <li>Maintain 100% offline access with efficient local Room database storage.</li>
            </ul>
        </div>
    </div>
</section>
@endsection
