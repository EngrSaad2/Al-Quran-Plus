@extends('layouts.admin')

@section('title', 'Dashboard - Al Quran Admin')
@section('page-title', 'System Dashboard & Metrics')

@section('content')
<!-- Stats Cards Grid -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-4">
        <a href="{{ route('admin.notifications.index') }}" class="text-decoration-none">
            <div class="stat-card d-flex align-items-center justify-content-between h-100 shadow-sm border-0 rounded-4 p-4 transition-all hover-lift" style="background: white; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;">
                <div>
                    <span class="text-muted fw-semibold small text-uppercase tracking-wider">Total Notifications</span>
                    <h2 class="fw-bold text-slate-800 mb-0 mt-1">{{ number_format($totalNotifications) }}</h2>
                    <span class="text-emerald small fw-semibold"><i class="fa-solid fa-arrow-right me-1"></i> View All</span>
                </div>
                <div class="icon-shape bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                    <i class="fa-solid fa-bell fs-4"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-xl-4">
        <a href="{{ route('admin.notifications.index', ['type' => 'all']) }}" class="text-decoration-none">
            <div class="stat-card d-flex align-items-center justify-content-between h-100 shadow-sm border-0 rounded-4 p-4 transition-all hover-lift" style="background: white; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;">
                <div>
                    <span class="text-muted fw-semibold small text-uppercase tracking-wider">Active Notifications</span>
                    <h2 class="fw-bold text-success mb-0 mt-1">{{ number_format($activeNotifications) }}</h2>
                    <span class="text-success small fw-semibold"><i class="fa-solid fa-circle-check me-1"></i> Manage Active</span>
                </div>
                <div class="icon-shape bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                    <i class="fa-solid fa-circle-check fs-4"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-xl-4">
        <a href="{{ route('admin.notifications.index') }}" class="text-decoration-none">
            <div class="stat-card d-flex align-items-center justify-content-between h-100 shadow-sm border-0 rounded-4 p-4 transition-all hover-lift" style="background: white; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;">
                <div>
                    <span class="text-muted fw-semibold small text-uppercase tracking-wider">Total Registered Devices</span>
                    <h2 class="fw-bold text-info mb-0 mt-1">{{ number_format($totalDevices) }}</h2>
                    <span class="text-info small fw-semibold"><i class="fa-solid fa-mobile-screen me-1"></i> FCM Targets</span>
                </div>
                <div class="icon-shape bg-info-subtle text-info rounded-circle d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                    <i class="fa-solid fa-mobile-screen-button fs-4"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-xl-6">
        <a href="{{ route('admin.notifications.index') }}" class="text-decoration-none">
            <div class="stat-card d-flex align-items-center justify-content-between h-100 shadow-sm border-0 rounded-4 p-4 transition-all hover-lift" style="background: white; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;">
                <div>
                    <span class="text-muted fw-semibold small text-uppercase tracking-wider">Today's Notifications</span>
                    <h2 class="fw-bold text-warning mb-0 mt-1">{{ number_format($todaysNotifications) }}</h2>
                    <span class="text-warning small fw-semibold"><i class="fa-solid fa-calendar-day me-1"></i> View Today</span>
                </div>
                <div class="icon-shape bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                    <i class="fa-solid fa-calendar-day fs-4"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-xl-6">
        <a href="{{ route('admin.notifications.index') }}" class="text-decoration-none">
            <div class="stat-card d-flex align-items-center justify-content-between h-100 shadow-sm border-0 rounded-4 p-4 transition-all hover-lift" style="background: white; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;">
                <div>
                    <span class="text-muted fw-semibold small text-uppercase tracking-wider">Sent Push Logs</span>
                    <h2 class="fw-bold text-purple mb-0 mt-1" style="color: #7E22CE;">{{ number_format($sentNotifications) }}</h2>
                    <span class="small fw-semibold" style="color: #7E22CE;"><i class="fa-solid fa-paper-plane me-1"></i> Broadcast History</span>
                </div>
                <div class="icon-shape rounded-circle d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; background: #F3E8FF; color: #7E22CE;">
                    <i class="fa-solid fa-paper-plane fs-4"></i>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Quick Action & Recent Items -->
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h6 class="fw-bold mb-0 text-slate-800"><i class="fa-solid fa-clock-rotate-left me-2 text-emerald"></i> Recent Notifications</h6>
                <a href="{{ route('admin.notifications.create') }}" class="btn btn-emerald text-white btn-sm fw-semibold rounded-pill px-3" style="background: #059669;">
                    <i class="fa-solid fa-plus me-1"></i> Create Notification
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="small text-muted">
                            <th>Title (EN / BN)</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentNotifications as $notif)
                            <tr style="cursor: pointer;" onclick="window.location='{{ route('admin.notifications.show', $notif->id) }}'">
                                <td>
                                    <div class="fw-bold text-slate-800">{{ Str::limit($notif->title_en, 35) }}</div>
                                    <div class="text-emerald small font-bangla">{{ Str::limit($notif->title_bn, 35) }}</div>
                                </td>
                                <td><span class="badge bg-secondary-subtle text-secondary text-uppercase">{{ $notif->notification_type }}</span></td>
                                <td>
                                    @if($notif->is_active)
                                        <span class="badge bg-success-subtle text-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-muted">Draft</span>
                                    @endif
                                </td>
                                <td class="small text-muted">{{ $notif->created_at->format('d M Y, h:i A') }}</td>
                                <td class="text-end" onclick="event.stopPropagation();">
                                    <a href="{{ route('admin.notifications.show', $notif->id) }}" class="btn btn-light btn-sm rounded-circle" title="View"><i class="fa-solid fa-eye text-primary"></i></a>
                                    <a href="{{ route('admin.notifications.edit', $notif->id) }}" class="btn btn-light btn-sm rounded-circle" title="Edit"><i class="fa-solid fa-pen text-slate-600"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No notifications recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
            <h6 class="fw-bold mb-4 text-slate-800"><i class="fa-solid fa-tower-broadcast me-2 text-emerald"></i> Quick Push Broadcast</h6>
            <p class="text-muted small">Send instant Firebase FCM push notification to registered devices.</p>
            <div class="d-flex flex-column gap-3 mt-2">
                <a href="{{ route('admin.notifications.create') }}" class="btn btn-outline-success py-3 rounded-3 text-start fw-bold shadow-sm d-flex align-items-center justify-content-between">
                    <span><i class="fa-solid fa-plus-circle me-2"></i> New Notification</span>
                    <i class="fa-solid fa-chevron-right small"></i>
                </a>
                <a href="{{ route('admin.notifications.index') }}" class="btn btn-outline-primary py-3 rounded-3 text-start fw-bold shadow-sm d-flex align-items-center justify-content-between">
                    <span><i class="fa-solid fa-list-check me-2"></i> Manage All Notifications</span>
                    <i class="fa-solid fa-chevron-right small"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
