@extends('layouts.admin')

@section('title', 'Manage Notifications - Al Quran Admin')
@section('page-title', 'Notification Management')

@section('content')
<div id="ajaxAlertContainer"></div>

<div class="card border-0 shadow-sm rounded-4 p-4">
    <!-- Top Bar Filter & Search -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <form action="{{ route('admin.notifications.index') }}" method="GET" class="d-flex flex-wrap align-items-center gap-2 flex-grow-1">
            <div class="input-group" style="max-width: 320px;">
                <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Search title or description..." value="{{ $filters['search'] ?? '' }}">
            </div>
            <select name="type" class="form-select w-auto">
                <option value="all">All Types</option>
                <option value="general" {{ ($filters['type'] ?? '') == 'general' ? 'selected' : '' }}>General</option>
                <option value="daily_hadith" {{ ($filters['type'] ?? '') == 'daily_hadith' ? 'selected' : '' }}>Daily Hadith</option>
                <option value="announcements" {{ ($filters['type'] ?? '') == 'announcements' ? 'selected' : '' }}>Announcements</option>
                <option value="ramadan" {{ ($filters['type'] ?? '') == 'ramadan' ? 'selected' : '' }}>Ramadan</option>
            </select>
            <button type="submit" class="btn btn-emerald text-white fw-semibold" style="background: #059669;">Filter</button>
        </form>

        <a href="{{ route('admin.notifications.create') }}" class="btn btn-emerald text-white fw-bold px-4 py-2 rounded-3 text-nowrap" style="background: #059669;">
            <i class="fa-solid fa-plus me-1"></i> Add Notification
        </a>
    </div>

    <!-- Notification Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr class="small text-muted">
                    <th>ID</th>
                    <th>Thumbnail</th>
                    <th>Title (EN / BN)</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Publish Date</th>
                    <th class="text-end">Actions & FCM Push</th>
                </tr>
            </thead>
            <tbody>
                @forelse($notifications as $item)
                    @php
                        $lastLog = $item->logs()->where('status', 'sent')->latest('sent_at')->first();
                        $sentCount = $item->logs()->where('status', 'sent')->count();
                        $lastSentTs = $lastLog ? $lastLog->sent_at->timestamp : 0;
                        $diffSec = $lastSentTs ? (time() - $lastSentTs) : 99999;
                        $isRecentSent = ($diffSec < 300);
                    @endphp
                    <tr id="row-{{ $item->id }}">
                        <td class="fw-bold">#{{ $item->id }}</td>
                        <td>
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" class="rounded-3" style="width: 44px; height: 44px; object-fit: cover;">
                            @else
                                <div class="bg-emerald-subtle rounded-3 d-flex align-items-center justify-content-center p-1" style="width: 44px; height: 44px;">
                                    <img src="{{ asset('favicon.png') }}" alt="Quran Logo" style="width: 32px; height: 32px; object-fit: contain;">
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-bold text-slate-800">{{ $item->title_en }}</div>
                            <div class="text-emerald small font-bangla">{{ $item->title_bn }}</div>
                        </td>
                        <td><span class="badge bg-info-subtle border border-info text-uppercase fw-semibold">{{ $item->notification_type }}</span></td>
                        <td id="status-cell-{{ $item->id }}">
                            @if($item->is_active)
                                <span class="badge bg-success-subtle border border-success">Active</span>
                            @else
                                <span class="badge bg-secondary-subtle border text-muted">Draft</span>
                            @endif

                            @if($sentCount > 0)
                                <span class="badge bg-emerald-subtle border border-emerald ms-1 sent-badge"><i class="fa-solid fa-check me-1"></i> Sent (<span class="sent-count">{{ $sentCount }}</span>)</span>
                            @endif
                        </td>
                        <td class="small text-muted">
                            {{ $item->publish_date ? $item->publish_date->format('d M Y, h:i A') : 'Instant' }}
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex align-items-center gap-1">
                                <!-- Send / Resend FCM Push Button Trigger -->
                                <span id="push-btn-container-{{ $item->id }}" data-id="{{ $item->id }}">
                                    @if($isRecentSent)
                                        <button type="button" class="btn btn-secondary btn-sm fw-bold px-2 rounded-3 text-white push-btn-gray" data-bs-toggle="modal" data-bs-target="#pushModal{{ $item->id }}" title="Sent recently. Click to resend.">
                                            <i class="fa-solid fa-check me-1"></i> Sent (<span class="cooldown-timer" data-sent-ts="{{ $lastSentTs }}">5m</span>)
                                        </button>
                                    @elseif($sentCount > 0)
                                        <button type="button" class="btn btn-success btn-sm fw-bold px-2 rounded-3 text-white push-btn-resend" data-bs-toggle="modal" data-bs-target="#pushModal{{ $item->id }}" title="Resend Firebase Push Notification">
                                            <i class="fa-solid fa-rotate-right me-1"></i> Resend
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-warning btn-sm fw-bold px-2 rounded-3 text-dark push-btn-push" data-bs-toggle="modal" data-bs-target="#pushModal{{ $item->id }}" title="Send Firebase Push Notification">
                                            <i class="fa-solid fa-paper-plane me-1"></i> Push
                                        </button>
                                    @endif
                                </span>

                                <a href="{{ route('admin.notifications.show', $item->id) }}" class="btn btn-light btn-sm rounded-circle" title="View"><i class="fa-solid fa-eye text-primary"></i></a>
                                <a href="{{ route('admin.notifications.edit', $item->id) }}" class="btn btn-light btn-sm rounded-circle" title="Edit"><i class="fa-solid fa-pen text-slate-600"></i></a>

                                <form action="{{ route('admin.notifications.duplicate', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-light btn-sm rounded-circle" title="Duplicate"><i class="fa-solid fa-copy text-info"></i></button>
                                </form>

                                <form action="{{ route('admin.notifications.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this notification?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-light btn-sm rounded-circle" title="Delete"><i class="fa-solid fa-trash text-danger"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">No notifications found matching your search.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination links -->
    <div class="mt-4">
        {{ $notifications->withQueryString()->links() }}
    </div>
</div>

<!-- Modals outside table -->
@foreach($notifications as $item)
    @php
        $sentCount = $item->logs()->where('status', 'sent')->count();
    @endphp
    <div class="modal fade" id="pushModal{{ $item->id }}" tabindex="-1" aria-labelledby="pushModalLabel{{ $item->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-start">
                <form action="{{ route('admin.notifications.send-push', $item->id) }}" method="POST" class="ajax-push-form" data-id="{{ $item->id }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="pushModalLabel{{ $item->id }}"><i class="fa-solid fa-paper-plane text-warning me-2"></i> Broadcast FCM Push</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="small text-muted mb-3">Send instant push notification for <strong>"{{ $item->title_en }}"</strong> to targeted devices.</p>
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
                        <button type="submit" class="btn btn-emerald text-white fw-bold submit-btn" style="background: #059669;">
                            <i class="fa-solid fa-paper-plane me-1"></i> <span class="submit-btn-text">Send Notification Now</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endsection

@push('scripts')
<script>
    function renderGraySentButton(id, sentTs) {
        var container = $('#push-btn-container-' + id);
        container.html(
            '<button type="button" class="btn btn-secondary btn-sm fw-bold px-2 rounded-3 text-white push-btn-gray" data-bs-toggle="modal" data-bs-target="#pushModal' + id + '" title="Sent recently. Click to resend.">' +
            '<i class="fa-solid fa-check me-1"></i> Sent (<span class="cooldown-timer" data-sent-ts="' + sentTs + '">5m</span>)</button>'
        );
    }

    function renderGreenResendButton(id) {
        var container = $('#push-btn-container-' + id);
        container.html(
            '<button type="button" class="btn btn-success btn-sm fw-bold px-2 rounded-3 text-white push-btn-resend" data-bs-toggle="modal" data-bs-target="#pushModal' + id + '" title="Resend Firebase Push Notification">' +
            '<i class="fa-solid fa-rotate-right me-1"></i> Resend</button>'
        );
    }

    function updateCooldownTimers() {
        var now = Math.floor(Date.now() / 1000);
        $('.cooldown-timer').each(function() {
            var el = $(this);
            var sentTs = parseInt(el.attr('data-sent-ts') || 0);
            if (!sentTs) return;

            var elapsed = now - sentTs;
            var remaining = 300 - elapsed;

            if (remaining <= 0) {
                var container = el.closest('[id^="push-btn-container-"]');
                if (container.length > 0) {
                    var id = container.attr('data-id');
                    renderGreenResendButton(id);
                }
            } else {
                var mins = Math.floor(remaining / 60);
                var secs = remaining % 60;
                var timeStr = mins + 'm ' + (secs < 10 ? '0' : '') + secs + 's';
                el.text(timeStr);
            }
        });
    }

    $(document).ready(function() {
        setInterval(updateCooldownTimers, 1000);
        updateCooldownTimers();

        $('.ajax-push-form').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var id = form.data('id');
            var modal = $('#pushModal' + id);
            var submitBtn = form.find('.submit-btn');
            var btnText = form.find('.submit-btn-text');
            var originalText = btnText.text();

            submitBtn.prop('disabled', true);
            btnText.html('<i class="fa-solid fa-spinner fa-spin me-1"></i> Sending...');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                dataType: 'json',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    modal.modal('hide');
                    submitBtn.prop('disabled', false);
                    btnText.text('Send Notification Now');

                    // 1. Immediately switch button to 5-minute Gray Sent button with live timer
                    renderGraySentButton(id, response.last_sent_at);

                    // 2. Update status badge
                    var statusCell = $('#status-cell-' + id);
                    if (statusCell.find('.sent-badge').length > 0) {
                        statusCell.find('.sent-count').text(response.sent_count);
                    } else {
                        statusCell.append(' <span class="badge bg-emerald-subtle border border-emerald ms-1 sent-badge"><i class="fa-solid fa-check me-1"></i> Sent (<span class="sent-count">' + response.sent_count + '</span>)</span>');
                    }

                    // 3. Trigger Unmissable Success Modal Popup!
                    if (typeof window.showPushSuccessModal === 'function') {
                        window.showPushSuccessModal(response.message);
                    }
                },
                error: function(xhr) {
                    submitBtn.prop('disabled', false);
                    btnText.text(originalText);
                    var errMsg = 'FCM Push Failed';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errMsg = xhr.responseJSON.message;
                    }
                    alert('Error: ' + errMsg);
                }
            });
        });
    });
</script>
@endpush
