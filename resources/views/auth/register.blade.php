@extends('layouts.front')

@section('title', 'عقار غزة - تسجيل في الموقع')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/register_styles.css') }}" />
@endpush

@section('content')
    <div class="signup_box">
        <div class="form-section">
            <h1 class="main-title">سجّل الآن</h1>
            <p class="subtitle">
                دعونا نجعلكم جميعًا مستعدين حتى تتمكنوا من الوصول إلى حسابكم الشخصي.
            </p>

            <!-- النموذج -->
            <form class="register-form" action="{{ route('register') }}" method="POST">
                @csrf
                <!-- الاسم الرباعي -->
                <div class="row single">
                    <div class="form-group full">
                        <label for="fullname">الاسم</label>
                        <!-- الحاوية التي ترسم الإطار وتحتوي على الحقل -->
                        <div class="field-signup_box">
                            <input type="text" id="fullname" name="name" placeholder="مثال: محمد عبدالله أحمد حمدان"
                                required />
                        </div>
                        @error('name')
                            <small style="color: red; margin-top:5px">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="row single">
                    <div class="form-group full">
                        <label for="email">البريد الإلكتروني</label>
                        <div class="field-signup_box">
                            <input type="email" id="email" name="email" placeholder="mohammed@gmail.com" required />
                        </div>
                        @error('email')
                            <small style="color: red; margin-top:5px">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="form-group half">
                        <label for="password">كلمة المرور</label>
                        <div class="field-signup_box">
                            <input type="password" id="password" name="password" placeholder="••••••••" required />
                        </div>
                        @error('password')
                            <small style="color: red; margin-top:5px">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group half">
                        <label for="confirm-password">تأكيد كلمة المرور</label>
                        <div class="field-signup_box">
                            <input type="password" id="confirm-password" name="password_confirmation" placeholder="••••••••"
                                required />
                        </div>
                        @error('password_confirmation')
                            <small style="color: red; margin-top:5px">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn-submit">إنشاء حساب</button>
            </form>

            <p class="already-have-account">
                لديك حساب بالفعل؟
                <a href="{{ route('login') }}" class="login-link">تسجيل الدخول</a>
            </p>

            <div class="divider-text">أو قم بالتسجيل مع</div>

            <!-- وسائل التواصل للتسجيل -->
            <div class="social-icons">
                <a href="{{ route('auth.redirect', 'google') }}" class="icon-wrapper">
                    <img src="{{ asset('assets/img/google.png') }}" alt="google  " class="illustration-image" />
                </a>

            </div>
        </div>

        <!-- قسم الصورة -->
        <div class="illustration-section">
            <img src="{{ asset('assets/img/account_settings.png') }}" alt="توضيح تسجيل الدخول"
                class="illustration-image" />
        </div>
    </div>

@endsection
