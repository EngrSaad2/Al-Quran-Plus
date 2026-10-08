@extends('layouts.admin')

@section('title', 'Change Password - Al Quran Admin')
@section('page-title', 'Change Account Password')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h5 class="fw-bold mb-3 text-slate-800"><i class="fa-solid fa-key text-emerald me-2"></i> Update Password</h5>
            <p class="text-muted small mb-4">Please update your default system password to secure your admin account.</p>

            <form action="{{ route('admin.change-password') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold small text-slate-700">Current Password</label>
                    <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small text-slate-700">New Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold small text-slate-700">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-emerald text-white fw-bold w-100 py-2 rounded-3" style="background: #059669;">
                    Update & Save Password
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
