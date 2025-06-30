<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>سجّل الآن</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/css/all.min.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />

</head>

<body>
    <div class="container">
        <div class="form-section">
            <h1 class="main-title">سجّل الآن</h1>
            <p class="subtitle">
                دعونا نجعلكم جميعًا مستعدين حتى تتمكنوا من الوصول إلى حسابكم الشخصي. </p>

            <!-- النموذج -->
            <form class="register-form" method="POST" action="{{ route('register') }}">
                @csrf
                <!-- الاسم الرباعي -->
                <div class="row single">
                    <div class="form-group full">
                        <label for="fullname">الاسم الرباعي</label>
                        <!-- الحاوية التي ترسم الإطار وتحتوي على الحقل -->
                        <div class="field-container">
                            <input value="{{ old('name') }}" type="text" id="fullname" name="name"
                                placeholder="مثال: محمد عبدالله أحمد حمدان" required />
                        </div>
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row single">
                    <div class="form-group full">
                        <label for="email">البريد الإلكتروني</label>
                        <div class="field-container">
                            <input type="email" id="email" name="email" placeholder="john.doe@gmail.com"
                                value="{{ old('email') }}" required />
                        </div>
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>
                </div>

                <div class="row">
                    <div class="form-group half">
                        <label for="password">كلمة المرور</label>
                        <div class="field-container">
                            <input type="password" id="password" name="password" placeholder="••••••••" required />
                        </div>
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group half">
                        <label for="confirm-password">تأكيد كلمة المرور</label>
                        <div class="field-container">
                            <input type="password" id="confirm-password" name="password_confirmation"
                                placeholder="••••••••" required />

                        </div>
                        @error('password_confirmation')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                {{-- <div class="policy-agreement">
                    <input type="checkbox" id="agree" name="agree" required />
                    <label for="agree">أوافق على الشروط وسياسة الخصوصية</label>
                </div> --}}

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
                    <img src="{{ asset('assets/img/google.png') }}" alt="google  " class="illustration-image" /> </a>
                {{-- <a href="{{ route('auth.redirect', 'facebook') }}" class="icon-wrapper">
                    <img src="{{ asset('assets/img/facebook.png') }}" alt="facebook  " class="illustration-image" />
                </a> --}}
            </div>
        </div>

        <!-- قسم الصورة -->
        <div class="illustration-section">
            <img src="{{ asset('assets/img/new.png') }}" alt="توضيح تسجيل الدخول" class="illustration-image" />
        </div>
    </div>
</body>

</html>
