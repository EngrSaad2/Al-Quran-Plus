<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Al Quran</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #031B15 0%, #064E3B 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: rgba(17, 34, 31, 0.95);
            border: 1px solid rgba(16, 185, 129, 0.3);
            border-radius: 24px;
            padding: 40px;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
        }

        .btn-quran {
            background: #10B981;
            color: #FFFFFF;
            font-weight: 600;
            padding: 12px;
            border-radius: 12px;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-quran:hover {
            background: #059669;
            color: #FFFFFF;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

    <div class="login-card text-white">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('public.home') }}" class="btn btn-outline-success btn-sm rounded-pill px-3 text-emerald border-emerald text-decoration-none fw-semibold" style="font-size: 0.85rem;">
                <i class="fa-solid fa-house me-1"></i> Home
            </a>
        </div>
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center p-2 mb-3">
                <img src="{{ asset('favicon.png') }}" alt="Al Quran Logo" style="width: 64px; height: 64px; object-fit: contain; filter: drop-shadow(0 4px 12px rgba(16, 185, 129, 0.4));">
            </div>
            <h3 class="fw-bold mb-1">Al Quran Admin</h3>
            <p class="text-light-subtle small">Notification Management Portal</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger border-0 small mb-3">
                <i class="fa-solid fa-circle-exclamation me-1"></i> {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label text-light-subtle small fw-bold">Email Address</label>
                <input type="email" name="email" class="form-control bg-dark border-secondary text-white p-3" value="{{ old('email') }}" required autofocus autocomplete="off">
            </div>
            <div class="mb-4">
                <label class="form-label text-light-subtle small fw-bold">Password</label>
                <input type="password" name="password" class="form-control bg-dark border-secondary text-white p-3" required autocomplete="current-password">
            </div>
            <div class="d-flex justify-content-between align-items-center mb-4 small">
                <div class="form-check">
                    <input class="form-check-input bg-dark border-secondary" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label text-light-subtle" for="remember">Remember me</label>
                </div>
            </div>
            <button type="submit" class="btn btn-quran w-100 fs-6">Sign In to Dashboard</button>
        </form>
    </div>

</body>
</html>
