@extends('layouts.public')

@section('title', 'Privacy Policy - Al Quran Application')

@section('content')
<section class="py-5">
    <div class="container py-4">
        <div class="max-w-800 mx-auto bg-dark p-4 p-md-5 rounded-4 border border-secondary">
            <h1 class="fw-bold text-emerald mb-4">Privacy Policy</h1>
            <p class="text-light-subtle">Last updated: July 2026</p>
            <hr class="border-emerald opacity-25 my-4">
            <h4 class="text-white fw-bold">1. Information We Collect</h4>
            <p class="text-light-subtle">We collect device tokens (FCM token), language preference, device platform, and app version solely for delivering push notifications and app updates. We do not sell or track personal user identities.</p>

            <h4 class="text-white fw-bold mt-4">2. Location Permissions</h4>
            <p class="text-light-subtle">Location permissions are requested strictly to calculate accurate local Islamic Prayer Times and Qibla direction. Location coordinates are processed locally on device and never stored on remote servers.</p>

            <h4 class="text-white fw-bold mt-4">3. Firebase Cloud Messaging (FCM)</h4>
            <p class="text-light-subtle">We utilize Google Firebase Cloud Messaging to send push notifications. You may disable notification permissions anytime via system application settings.</p>

            <h4 class="text-white fw-bold mt-4">4. Contact Us</h4>
            <p class="text-light-subtle">If you have any questions regarding this Privacy Policy, contact us at: <a href="mailto:saad@quran.com" class="text-emerald">saad@quran.com</a>.</p>
        </div>
    </div>
</section>
@endsection
