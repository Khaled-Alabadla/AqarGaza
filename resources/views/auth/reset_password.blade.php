@extends('layouts.front')

@section('title', 'إعادة تعيين كلمة المرور')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/reset_styles.css') }}">
@endpush

@section('content')
    <div class="reset">
        <div class="reset-password-container">
            <div class="reset-password-card">
                <div class="lock-icon-wrapper">
                    <i class="fas fa-lock"></i>
                </div>
                <h1 class="title">إعادة تعيين كلمة المرور</h1>
                <p class="description">قم بإدخال كلمة المرور الجديدة الخاصة بك</p>

                <form id="resetPasswordForm" class="reset-password-form" action="{{ route('password.store') }}"
                    method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="email">البريد الإلكتروني</label>
                        <input type="email" name="email" id="email" placeholder="email@example.com" required>
                        @error('email')
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                    </div>
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="form-group">
                        <label for="new-password">كلمة المرور</label>
                        <input type="password" name="password" id="new-password" placeholder="Password" required>
                        @error('password')
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="confirm-password">تأكيد كلمة المرور</label>
                        <input type="password" name="password_confirmation" id="confirm-password"
                            placeholder="Confirm password" required>
                    </div>
                    <button type="submit" class="submit-btn">إعادة تعيين كلمة المرور</button>
                </form>
            </div>
        </div>
    </div>
@endsection
