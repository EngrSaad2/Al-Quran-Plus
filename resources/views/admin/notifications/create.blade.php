@extends('layouts.admin')

@section('title', 'Create Notification - Al Quran Admin')
@section('page-title', 'Create New Notification')

@section('content')
<div class="card border-0 shadow-sm rounded-4 p-4">
    <form action="{{ route('admin.notifications.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row g-4">
            <!-- Left Side: Multilingual Details -->
            <div class="col-lg-8">
                <!-- English Section -->
                <div class="p-3 rounded-3 bg-light mb-4 border">
                    <h6 class="fw-bold text-primary mb-3"><i class="fa-solid fa-language me-2"></i> English Content</h6>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Title (English) <span class="text-danger">*</span></label>
                        <input type="text" name="title_en" class="form-control" placeholder="Enter title in English..." value="{{ old('title_en') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Short Description (English - max 2 lines) <span class="text-danger">*</span></label>
                        <textarea name="short_description_en" rows="2" class="form-control" placeholder="Brief summary displayed in list..." required>{{ old('short_description_en') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Full Content (English - Rich HTML) <span class="text-danger">*</span></label>
                        <textarea name="content_en" class="form-control summernote" rows="5" required>{{ old('content_en') }}</textarea>
                    </div>
                </div>

                <!-- Bangla Section -->
                <div class="p-3 rounded-3 bg-light mb-4 border">
                    <h6 class="fw-bold text-success mb-3"><i class="fa-solid fa-language me-2"></i> Bangla Content (বাংলা)</h6>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">শিরোনাম (বাংলা) <span class="text-danger">*</span></label>
                        <input type="text" name="title_bn" class="form-control font-bangla" placeholder="বাংলা শিরোনাম লিখুন..." value="{{ old('title_bn') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">সংক্ষিপ্ত বিবরণ (বাংলা - সর্বোচ্চ ২ লাইন) <span class="text-danger">*</span></label>
                        <textarea name="short_description_bn" rows="2" class="form-control font-bangla" placeholder="তালিকায় প্রদর্শনের জন্য সংক্ষিপ্ত বিবরণ..." required>{{ old('short_description_bn') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">বিস্তারিত বিবরণ (বাংলা - Rich HTML) <span class="text-danger">*</span></label>
                        <textarea name="content_bn" class="form-control summernote" rows="5" required>{{ old('content_bn') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Right Side: Publishing Controls & Media -->
            <div class="col-lg-4">
                <!-- Media Uploads -->
                <div class="card border-0 bg-light p-3 mb-4 border">
                    <h6 class="fw-bold mb-3"><i class="fa-solid fa-image me-2 text-emerald"></i> Thumbnail & Banner</h6>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Thumbnail Image (List icon)</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Banner Image (Details screen hero image)</label>
                        <input type="file" name="banner_image" class="form-control" accept="image/*">
                    </div>
                </div>

                <!-- Notification Options -->
                <div class="card border-0 bg-light p-3 mb-4 border">
                    <h6 class="fw-bold mb-3"><i class="fa-solid fa-sliders me-2 text-emerald"></i> Category & Publishing</h6>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Notification Type</label>
                        <select name="notification_type" class="form-select">
                            <option value="general">General</option>
                            <option value="daily_hadith">Daily Hadith</option>
                            <option value="announcements">Announcements</option>
                            <option value="ramadan">Ramadan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Publishing Action</label>
                        <select name="action_type" class="form-select" id="actionTypeSelect">
                            <option value="publish_now">Publish Immediately</option>
                            <option value="schedule">Schedule for Later</option>
                            <option value="draft">Save as Draft</option>
                        </select>
                    </div>
                    <div class="mb-3 d-none" id="scheduleDateContainer">
                        <label class="form-label fw-bold small">Scheduled Date & Time</label>
                        <input type="datetime-local" name="publish_date" class="form-control">
                    </div>
                </div>

                <!-- Firebase Push Notification Option -->
                <div class="card border border-warning bg-warning-subtle p-3 mb-4">
                    <h6 class="fw-bold text-dark mb-2"><i class="fa-solid fa-paper-plane me-2 text-warning"></i> Firebase Push Trigger</h6>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="send_push_now" value="1" id="sendPushSwitch" checked>
                        <label class="form-check-label fw-bold small text-dark" for="sendPushSwitch">Send Push Notification Immediately</label>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-bold small text-dark">Target Audience</label>
                        <select name="target_audience" class="form-select form-select-sm">
                            <option value="all_users">All Users (topic: all_users)</option>
                            <option value="english">English Speakers (topic: english)</option>
                            <option value="bangla">Bangla Speakers (topic: bangla)</option>
                        </select>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-emerald text-white fw-bold py-3 rounded-3" style="background: #059669;">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Save Notification
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

        $('#actionTypeSelect').on('change', function() {
            if ($(this).val() === 'schedule') {
                $('#scheduleDateContainer').removeClass('d-none');
            } else {
                $('#scheduleDateContainer').addClass('d-none');
            }
        });
    });
</script>
@endpush
