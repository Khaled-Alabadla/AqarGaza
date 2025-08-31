@extends('layouts.front')

@section('title', 'تسجيل الدخول')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/login_styles.css') }}" />
@endpush

@section('content')
    <div class="login_box">
        <div class="form-section">
            <h1 class="welcome-text">أهلا بكم في عقاري</h1>

            <div class="social-login">
                <a class="social-login-button google-button" style="text-decoration: none"
                    href="{{ route('auth.redirect', 'google') }}">
                    <span class="icon-wrapper google">
                        <img style="max-width: 120%;" src="{{ asset('assets/img/google.png') }}" alt="google"
                            class="social-image" />
                    </span>
                    <span class="google"> تسجيل الدخول عن طريق جوجل
                    </span>
                </a>

            </div>

            <div class="or-divider">أو</div>

            <form class="login-form" action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email">البريد الإلكتروني</label>
                    <div class="field-login_box">
                        <input class="@error('email') is-invalid @enderror" type="email" value="{{ old('email') }}"
                            id="email" name="email" placeholder="example@gmail.com" required />
                    </div>
                    @error('email')
                        <small style="color: red; margin-top:5px">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">كلمة المرور</label>
                    <div class="field-login_box">
                        <input class="@error('password') is-invalid @enderror" type="password" id="password"
                            name="password" placeholder="••••••••" required />
                    </div>
                    @error('password')
                        <small style="color: red; margin-top:5px">{{ $message }}</small>
                    @enderror
                </div>

                <div class="extra-options">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember" />
                        <label for="remember">تذكرني</label>
                    </div>
                    <a href="{{ route('password.request') }}" class="forgot-password">هل نسيت كلمة المرور؟</a>
                </div>

                <button type="submit" class="login-button">دخول</button>
            </form>

            <div class="register-link">
                <span>ليس لديك حساب؟</span>
                <a href="{{ route('register') }}">سجل الآن</a>
            </div>
        </div>

        <div class="illustration-section">
            <img src="{{ asset('assets/img/landing.jpg') }}" alt="صورة توضيحية" class="illustration-image" />
        </div>
    </div>
@endsection
