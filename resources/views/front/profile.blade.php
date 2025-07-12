@extends('layouts.front')

@section('title', 'البيانات الشخصية')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/profile_styles.css') }}">
@endpush

@section('content')
    <div class="add">

        <div class="form-container">
            <section class="account-section">
                <div class="section-header">
                    <h2>تعديل الملف الشخصي</h2>
                </div>

                <form action="{{ route('front.profile') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="profile-pic-section">
                        <div class="profile-pic-wrapper">
                            <img id="profile-image-display" src="{{ asset($user->image) ?? asset('assets/img/ho.png') }}"
                                alt="صورة المستخدم">
                            <label for="profile-image-upload" class="upload-icon-wrapper">
                                <i class="fas fa-camera"></i>
                                <input type="file" name="image" id="profile-image-upload" accept="image/*"
                                    style="display: none;">
                            </label>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="full-name">الاسم رباعي</label>
                            <input type="text" name="name" id="full-name" value="{{ old('name', $user->name) }}">
                            @error('name')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                        </div>

                    </div>

                    <div class="form-group">
                        <label for="phone-number">رقم الهاتف</label>
                        <input type="text" name="phone" id="phone-number" value="{{ old('phone', $user->phone) }}">
                        @error('phone')
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                    </div>
                    @dump($errors->all())
                    <div class="form-group">
                        <label for="email-address">الإيميل</label>
                        <input type="email" name="email" id="email-address" value="{{ old('email', $user->email) }}">
                        @error('email')
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="address">العنوان</label>
                        <input type="text" name="address" id="address" value="{{ old('address', $user->address) }}">
                        @error('address')
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit" class="edit-btn">تعديل</button>
                </form>
            </section>
        </div>
        <div class="side-info">
            <div class="info-card help-card">
                <i class="fas fa-question-circle"></i>
                <h3>تحتاج لمساعدة؟ تواصل معنا</h3>
                <p>لا تتردد في التواصل معنا إذا كان لديك استفسار أو تحتاج لمساعدة، نحن نعمل من أجلك لخدمتك.</p>
            </div>

            <div class="info-card privacy-card">
                <i class="fas fa-file-alt"></i>
                <h3>سياسة الخصوصية</h3>
                <p>تم إعدادها لتطبيق وموقع بائع للإمدادات العقارية يجب الالتزام بالتحكم والوصول بمرونة الاستخدام.</p>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/profile.js') }}"></script>
@endpush
</body>


</html>
