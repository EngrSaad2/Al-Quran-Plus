@extends('layouts.public')

@section('title', 'Contact Us - Al Quran Application')

@section('content')
<section class="py-5">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="bg-dark p-4 p-md-5 rounded-4 border border-secondary">
                    <h1 class="fw-bold text-emerald mb-3">Contact Us</h1>
                    <p class="text-light-subtle mb-4">Have feedback, questions, or bug reports? Reach out to us directly.</p>

                    @if(session('success'))
                        <div class="alert alert-success border-0 bg-success-subtle text-success fw-medium mb-4">
                            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('public.contact.submit') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-light small fw-bold">Your Name</label>
                                <input type="text" name="name" class="form-bg-dark form-control border-secondary text-white" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-light small fw-bold">Your Email</label>
                                <input type="email" name="email" class="form-bg-dark form-control border-secondary text-white" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-light small fw-bold">Subject</label>
                                <input type="text" name="subject" class="form-bg-dark form-control border-secondary text-white" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-light small fw-bold">Message</label>
                                <textarea name="message" rows="5" class="form-bg-dark form-control border-secondary text-white" required></textarea>
                            </div>
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-quran-primary w-100 py-3">Send Message</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
