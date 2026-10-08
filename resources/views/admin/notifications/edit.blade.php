@extends('layouts.admin')

@section('title', 'Edit Notification - Al Quran Admin')
@section('page-title', 'Edit Notification #' . $notification->id)

@section('content')
<div class="card border-0 shadow-sm rounded-4 p-4">
    <form action="{{ route('admin.notifications.update', $notification->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row g-4">
            <!-- Left Side: Details -->
            <div class="col-lg-8">
                <!-- English Section -->
                <div class="p-3 rounded-3 bg-light mb-4 border">
                    <h6 class="fw-bold text-primary mb-3"><i class="fa-solid fa-language me-2"></i> English Content</h6>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Title (English) <span class="text-danger">*</span></label>
                        <input type="text" name="title_en" class="form-control" value="{{ old('title_en', $notification->title_en) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Short Description (English - max 2 lines) <span class="text-danger">*</span></label>
                        <textarea name="short_description_en" rows="2" class="form-control" required>{{ old('short_description_en', $notification->short_description_en) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Full Content (English - Rich HTML) <span class="text-danger">*</span></label>
                        <textarea name="content_en" class="form-control summernote" rows="5" required>{{ old('content_en', $notification->content_en) }}</textarea>
                    </div>
                </div>

                <!-- Bangla Section -->
                <div class="p-3 rounded-3 bg-light mb-4 border">
                    <h6 class="fw-bold text-success mb-3"><i class="fa-solid fa-language me-2"></i> Bangla Content (বাংলা)</h6>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">শিরোনাম (বাংলা) <span class="text-danger">*</span></label>
                        <input type="text" name="title_bn" class="form-control font-bangla" value="{{ old('title_bn', $notification->title_bn) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">সংক্ষিপ্ত বিবরণ (বাংলা - সর্বোচ্চ ২ লাইন) <span class="text-danger">*</span></label>
                        <textarea name="short_description_bn" rows="2" class="form-control font-bangla" required>{{ old('short_description_bn', $notification->short_description_bn) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">বিস্তারিত বিবরণ (বাংলা - Rich HTML) <span class="text-danger">*</span></label>
                        <textarea name="content_bn" class="form-control summernote" rows="5" required>{{ old('content_bn', $notification->content_bn) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Right Side: Publishing Controls & Media -->
            <div class="col-lg-4">
                <!-- Media Uploads -->
                <div class="card border-0 bg-light p-3 mb-4 border">
                    <h6 class="fw-bold mb-3"><i class="fa-solid fa-image me-2 text-emerald"></i> Thumbnail & Banner</h6>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Thumbnail Image</label>
                        @if($notification->image_url)
                            <div class="mb-2"><img src="{{ $notification->image_url }}" class="rounded img-fluid border shadow-sm" style="max-height: 120px; object-fit: cover;"></div>
                        @endif
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Banner Image</label>
                        @if($notification->banner_image_url)
                            <div class="mb-2"><img src="{{ $notification->banner_image_url }}" class="rounded img-fluid border shadow-sm" style="max-height: 140px; object-fit: cover;"></div>
                        @endif
                        <input type="file" name="banner_image" class="form-control" accept="image/*">
                    </div>
                </div>

                <!-- Notification Options -->
                <div class="card border-0 bg-light p-3 mb-4 border">
                    <h6 class="fw-bold mb-3"><i class="fa-solid fa-sliders me-2 text-emerald"></i> Category & Publishing</h6>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Notification Type</label>
                        <select name="notification_type" class="form-select">
                            <option value="general" {{ $notification->notification_type === 'general' ? 'selected' : '' }}>General</option>
                            <option value="daily_hadith" {{ $notification->notification_type === 'daily_hadith' ? 'selected' : '' }}>Daily Hadith</option>
                            <option value="announcements" {{ $notification->notification_type === 'announcements' ? 'selected' : '' }}>Announcements</option>
                            <option value="ramadan" {{ $notification->notification_type === 'ramadan' ? 'selected' : '' }}>Ramadan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Status</label>
                        <select name="is_active" class="form-select">
                            <option value="1" {{ $notification->is_active ? 'selected' : '' }}>Active / Published</option>
                            <option value="0" {{ !$notification->is_active ? 'selected' : '' }}>Draft / Inactive</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Publish Date & Time</label>
                        <input type="datetime-local" name="publish_date" class="form-control" value="{{ $notification->publish_date ? $notification->publish_date->format('Y-m-d\TH:i') : '' }}">
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-emerald text-white fw-bold py-3 rounded-3" style="background: #059669;">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Update Changes
                    </button>
                    <a href="{{ route('admin.notifications.index') }}" class="btn btn-light border py-2">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            height: 180,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link']],
                ['view', ['codeview']]
            ]
        });
    });
</script>
@endpush
