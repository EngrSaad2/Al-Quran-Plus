@extends('layouts.admin')

@section('title', 'View Notification - Al Quran Admin')
@section('page-title', 'Notification Preview #' . $notification->id)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-emerald-subtle border border-emerald px-3 py-2 rounded-pill fw-bold">
                        {{ strtoupper($notification->notification_type) }}
                    </span>
                    @if($notification->logs()->where('status', 'sent')->count() > 0)
                        <span class="badge bg-success-subtle border border-success px-3 py-2 rounded-pill fw-bold">
                            <i class="fa-solid fa-check-circle me-1"></i> Push Sent ({{ $notification->logs()->where('status', 'sent')->count() }} times)
                        </span>
                    @else
                        <span class="badge bg-secondary-subtle border px-3 py-2 rounded-pill fw-bold">
                            Not Sent Yet
                        </span>
                    @endif
                </div>

                <div class="d-flex align-items-center gap-2">
                    @if($notification->logs()->where('status', 'sent')->count() > 0)
                        <button type="button" class="btn btn-success btn-sm fw-bold px-3 rounded-pill" data-bs-toggle="modal" data-bs-target="#pushModalShow">
                            <i class="fa-solid fa-rotate-right me-1"></i> Resend Push
                        </button>
                    @else
                        <button type="button" class="btn btn-warning btn-sm fw-bold px-3 rounded-pill text-dark" data-bs-toggle="modal" data-bs-target="#pushModalShow">
                            <i class="fa-solid fa-paper-plane me-1"></i> Send Push Now
                        </button>
                    @endif
                    <a href="{{ route('admin.notifications.edit', $notification->id) }}" class="btn btn-emerald text-white btn-sm px-3 rounded-pill" style="background: #059669;">
                        <i class="fa-solid fa-pen me-1"></i> Edit
                    </a>
                    <a href="{{ route('admin.notifications.index') }}" class="btn btn-light border btn-sm px-3 rounded-pill">Back</a>
                </div>
            </div>

            <!-- Thumbnail / Image Display -->
            @if($notification->banner_image)
                <img src="{{ asset('storage/' . $notification->banner_image) }}" class="rounded-4 img-fluid mb-4 w-100" style="max-height: 320px; object-fit: cover;">
            @elseif($notification->image)
                <img src="{{ asset('storage/' . $notification->image) }}" class="rounded-4 img-fluid mb-4 w-100" style="max-height: 240px; object-fit: cover;">
            @else
                <div class="text-center p-4 mb-4 bg-light rounded-4 border d-flex align-items-center justify-content-center gap-3">
                    <img src="{{ asset('favicon.png') }}" alt="Quran Logo" style="width: 64px; height: 64px; object-fit: contain;">
                    <div class="text-start">
                        <span class="badge bg-emerald-subtle border border-emerald mb-1">Default Logo Thumbnail</span>
                        <h6 class="fw-bold mb-0 text-slate-800">Al Quran Application Logo</h6>
                    </div>
                </div>
            @endif

            <!-- Tabs -->
            <ul class="nav nav-pills nav-fill bg-light p-1 rounded-3 mb-4" id="previewTabs">
                <li class="nav-item">
                    <button class="nav-link active fw-bold py-2 rounded-3" data-bs-toggle="tab" data-bs-target="#tab-en">English Preview</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link fw-bold py-2 rounded-3 text-emerald" data-bs-toggle="tab" data-bs-target="#tab-bn">Bangla Preview (বাংলা)</button>
                </li>
            </ul>

            <div class="tab-content">
                <!-- English Tab -->
                <div class="tab-pane fade show active" id="tab-en">
                    <h3 class="fw-bold text-slate-800 mb-2">{{ $notification->title_en }}</h3>
                    <p class="text-muted small mb-4"><i class="fa-solid fa-calendar me-1"></i> {{ $notification->publish_date ? $notification->publish_date->format('F d, Y h:i A') : $notification->created_at->format('F d, Y h:i A') }}</p>
                    <div class="p-3 bg-light rounded-3 mb-4 border fst-italic text-slate-700">
                        {{ $notification->short_description_en }}
                    </div>
                    <div class="prose max-w-none text-slate-800">
                        {!! $notification->content_en !!}
                    </div>
                </div>

                <!-- Bangla Tab -->
                <div class="tab-pane fade" id="tab-bn">
                    <h3 class="fw-bold text-slate-800 mb-2 font-bangla">{{ $notification->title_bn }}</h3>
                    <p class="text-muted small mb-4"><i class="fa-solid fa-calendar me-1"></i> {{ $notification->publish_date ? $notification->publish_date->format('d F, Y h:i A') : $notification->created_at->format('d F, Y h:i A') }}</p>
                    <div class="p-3 bg-light rounded-3 mb-4 border fst-italic text-slate-700 font-bangla">
                        {{ $notification->short_description_bn }}
                    </div>
                    <div class="prose max-w-none text-slate-800 font-bangla">
                        {!! $notification->content_bn !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FCM Push Modal -->
<div class="modal fade" id="pushModalShow" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-start">
            <form action="{{ route('admin.notifications.send-push', $notification->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-paper-plane text-warning me-2"></i> {{ $notification->logs()->where('status', 'sent')->count() > 0 ? 'Resend Push Notification' : 'Broadcast FCM Push' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted mb-3">Send instant Firebase FCM push notification for <strong>"{{ $notification->title_en }}"</strong> to targeted devices.</p>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Target Audience Topic</label>
                        <select name="target_audience" class="form-select">
                            <option value="all_users">All Users (topic: all_users)</option>
                            <option value="english">English Speakers (topic: english)</option>
                            <option value="bangla">Bangla Speakers (topic: bangla)</option>
                            <option value="daily_hadith">Daily Hadith Subscribers</option>
                            <option value="announcements">Announcements</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-emerald text-white fw-bold" style="background: #059669;">
                        <i class="fa-solid fa-paper-plane me-1"></i> {{ $notification->logs()->where('status', 'sent')->count() > 0 ? 'Resend Notification Now' : 'Send Push Now' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
