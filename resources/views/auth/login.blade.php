<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>أهلا بكم في عقاري</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/login_styles.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    {{-- <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script> --}}

</head>

<body>
    <div class="container">
        <div class="form-section">
            <h1 class="welcome-text">أهلا بكم في عقاري</h1>

            <div class="social-login">
                <a href="{{ route('auth.redirect', 'google') }}" class="social-login-button google-button">
                    <div class="icon-wrapper google">
                        <img style="max-width: 120%;" src="{{ asset('assets/img/google.png') }}" alt="google"
                            class="social-image" />
                    </div>
                    <span class="google"> تسجيل الدخول عن طريق جوجل
                    </span>
                </a>
                {{-- <a href="{{ route('auth.redirect', 'facebook') }}" class="social-login-button facebook-button">
                    <div class="icon-wrapper">
                        <img src="{{ asset('assets/img/facebook.png') }}" alt="facebook" class="social-image" />
                    </div>
                    تسجيل الدخول عن طريق فيسبوك
                </a> --}}
            </div>

            <div class="or-divider">أو</div>

            <form class="login-form" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label for="email">البريد الإلكتروني</label>
                    <div class="field-container">
                        <input class="@error('email') is-invalid @enderror" type="email" id="email" name="email"
                            placeholder="example@gmail.com" required />
                    </div>
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">كلمة المرور</label>
                    <div class="field-container">
                        <input type="password" class="@error('email') is-invalid @enderror" id="password"
                            name="password" placeholder="••••••••" required />
                    </div>
                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
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
            <img src="{{ asset('assets/img/login.png') }}" alt="صورة توضيحية" class="illustration-image" />
        </div>
    </div>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
</body>

</html>
