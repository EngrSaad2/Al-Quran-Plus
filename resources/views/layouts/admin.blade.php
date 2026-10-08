<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - Al Quran')</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <!-- Summernote Rich Text Editor -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs5.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 260px;
            --admin-bg: #F8FAFC;
            --admin-card: #FFFFFF;
            --admin-primary: #059669;
            --admin-dark: #064E3B;
        }

        .text-emerald { color: #059669 !important; }
        .bg-emerald { background-color: #059669 !important; }
        .bg-emerald-subtle { background-color: #D1FAE5 !important; color: #065F46 !important; }
        .border-emerald { border-color: #10B981 !important; }

        .bg-success-subtle { background-color: #D1FAE5 !important; color: #065F46 !important; }
        .bg-info-subtle { background-color: #E0F2FE !important; color: #0369A1 !important; }
        .bg-warning-subtle { background-color: #FEF3C7 !important; color: #92400E !important; }
        .bg-secondary-subtle { background-color: #F1F5F9 !important; color: #475569 !important; }
        .bg-danger-subtle { background-color: #FEE2E2 !important; color: #991B1B !important; }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--admin-bg);
            color: #1E293B;
        }

        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: #064E3B;
            color: #ECFDF5;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .sidebar-brand {
            padding: 20px 24px;
            font-size: 1.25rem;
            font-weight: 700;
            color: #34D399;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-menu {
            padding: 20px 12px;
            list-style: none;
            margin: 0;
        }

        .sidebar-menu .nav-link {
            color: #A7F3D0 !important;
            padding: 12px 16px;
            border-radius: 10px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 4px;
            transition: all 0.2s ease;
        }

        .sidebar-menu .nav-link:hover, .sidebar-menu .nav-link.active {
            background: rgba(16, 185, 129, 0.2);
            color: #FFFFFF !important;
        }

        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .top-navbar {
            background: #FFFFFF;
            border-bottom: 1px solid #E2E8F0;
            padding: 14px 28px;
        }

        .stat-card {
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 16px;
            padding: 24px;
            transition: all 0.2s ease;
        }

        .stat-card:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            transform: translateY(-2px);
        }

        .icon-shape {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
        }

        @media (max-width: 991.98px) {
            .sidebar {
                left: -260px;
                box-shadow: 4px 0 25px rgba(0, 0, 0, 0.3);
            }
            .sidebar.show {
                left: 0;
            }
            .main-wrapper {
                margin-left: 0;
            }
            .top-navbar {
                padding: 12px 16px;
            }
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            backdrop-filter: blur(2px);
        }

        .sidebar-overlay.show {
            display: block;
        }
    </style>
</head>
<body>

    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-brand d-flex align-items-center justify-content-between">
            <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none">
                <img src="{{ asset('favicon.png') }}" alt="Al Quran Logo" style="width: 32px; height: 32px; object-fit: contain; filter: drop-shadow(0 2px 6px rgba(16, 185, 129, 0.3));">
                <span class="fw-bold fs-5 text-emerald">Al Quran Admin</span>
            </a>
            <button class="btn text-white-50 d-lg-none p-0 border-0" id="sidebarCloseBtn">
                <i class="fa-solid fa-xmark fs-4"></i>
            </button>
        </div>
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-line fs-5"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('admin.notifications.index') }}" class="nav-link {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-bell fs-5"></i> Notifications
                </a>
            </li>
            <li>
                <a href="{{ route('admin.change-password') }}" class="nav-link {{ request()->routeIs('admin.change-password') ? 'active' : '' }}">
                    <i class="fa-solid fa-key fs-5"></i> Change Password
                </a>
            </li>
            <li class="mt-4 pt-3 border-top border-secondary opacity-50">
                <a href="{{ url('/') }}" target="_blank" class="nav-link">
                    <i class="fa-solid fa-globe fs-5"></i> Public Website <i class="fa-solid fa-up-right-from-square ms-auto small"></i>
                </a>
            </li>
        </ul>
    </aside>

    <!-- Main Content Area -->
    <div class="main-wrapper main-content">
        <!-- Top Navbar -->
        <header class="top-navbar d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-light d-lg-none rounded-3 border-0" id="sidebarToggleBtn">
                    <i class="fa-solid fa-bars fs-5"></i>
                </button>
                <h4 class="fw-bold mb-0 text-slate-800">@yield('page-title', 'Dashboard')</h4>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center gap-2 bg-light px-3 py-2 rounded-pill border">
                    <i class="fa-solid fa-user-check text-emerald"></i>
                    <span class="fw-semibold small text-slate-700">{{ Auth::user()->name ?? 'Engr Saad' }}</span>
                </div>
                <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-bold">
                        <i class="fa-solid fa-right-from-bracket me-1"></i> Logout
                    </button>
                </form>
            </div>
        </header>

        <!-- Dynamic Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-3 d-flex align-items-center gap-2 mb-4 alert-dismissible fade show">
                <i class="fa-solid fa-circle-check fs-5"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger border-0 shadow-sm rounded-3 d-flex align-items-center gap-2 mb-4 alert-dismissible fade show">
                <i class="fa-solid fa-circle-exclamation fs-5"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <main class="p-3 p-md-4 flex-grow-1">
            @yield('content')
        </main>
    </div>

    <!-- Global Push Success Modal Popup -->
    <div class="modal fade" id="pushSuccessModal" tabindex="-1" aria-hidden="true" style="z-index: 1080;">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg rounded-4 text-center p-4" style="background: #FFFFFF;">
                <div class="d-inline-flex align-items-center justify-content-center bg-success-subtle text-success rounded-circle mx-auto mb-3" style="width: 64px; height: 64px;">
                    <i class="fa-solid fa-paper-plane fs-2 text-emerald"></i>
                </div>
                <h5 class="fw-bold text-slate-800 mb-2">Notification Sent!</h5>
                <p class="text-muted small mb-4" id="pushSuccessModalMessage">Notification has sent successfully via Firebase FCM to all devices.</p>
                <button type="button" class="btn btn-emerald text-white fw-bold w-100 rounded-pill py-2" data-bs-dismiss="modal" style="background: #059669;">
                    <i class="fa-solid fa-check me-1"></i> Got It
                </button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs5.min.js"></script>
    <script>
        window.showPushSuccessModal = function(message) {
            if (message) {
                $('#pushSuccessModalMessage').text(message);
            }
            var modalEl = document.getElementById('pushSuccessModal');
            if (modalEl) {
                var modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                modal.show();
            }
        };

        $(document).ready(function() {
            // Check if URL query has push_sent flag or session flash
            @if(session('success') && str_contains(session('success'), 'FCM Push'))
                window.showPushSuccessModal("{{ session('success') }}");
            @endif

            // Off-Canvas Sidebar toggle for mobile
            var sidebar = $('.sidebar');
            var overlay = $('.sidebar-overlay');

            $('#sidebarToggleBtn, #sidebarToggle').on('click', function() {
                sidebar.addClass('show');
                overlay.addClass('show');
            });

            $('#sidebarCloseBtn, .sidebar-overlay').on('click', function() {
                sidebar.removeClass('show');
                overlay.removeClass('show');
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
