@extends('layouts.front')

@section('title', 'البيانات الشخصية')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/profile_styles.css') }}">
@endpush

<button class="chat-fab-simple" aria-label="الدردشات">
    <i class="fas fa-comment-dots"></i>
</button>

<aside class="chat-sidebar-simple" aria-hidden="true">
    <div class="chat-sidebar-header-simple">
        <h3>الدردشات</h3>
        <button class="chat-close-btn-simple" aria-label="إغلاق الدردشات">
            <i class="fas fa-xmark"></i>
        </button>
    </div>
    <ul class="chat-list-simple">
    </ul>
    <div class="chat-sidebar-footer-simple">
        <a href="chats.html" class="all-chats-btn-simple">كل الدردشات</a>
    </div>
</aside>

@section('content')
    <div class="add">

        <div class="form-container">
            <section class="account-section">
                <div class="section-header">
                    <h2>البيانات الشخصية</h2>
                    <p class="section-description-inline"> يمكنك تعديل بياناتك الشخصية </p>
                </div>

                <div class="profile-pic-section">
                    <div class="profile-pic-wrapper">
                        <img id="profile-image-display" src="{{ asset('assets/img/ho.png') }}" alt="صورة المستخدم">
                        <label for="profile-image-upload" class="upload-icon-wrapper">
                            <i class="fas fa-camera"></i>
                            <input type="file" id="profile-image-upload" accept="image/*" style="display: none;">
                        </label>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="full-name">الاسم رباعي</label>
                        <input type="text" id="full-name" value="Khaled Esam">
                    </div>

                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="gender">الجنس</label>
                        <select id="gender">
                            <option value="male">ذكر</option>
                            <option value="female">أنثى</option>
                            <option value="other">أخرى</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date-of-birth">تاريخ الميلاد</label>
                        <input type="date" id="date-of-birth">
                        <!-- <i class="far fa-calendar-alt date-icon"></i> -->
                    </div>
                </div>

                <div class="form-group">
                    <label for="phone-number">رقم الهاتف</label>
                    <input type="text" id="phone-number" value="+972596589274">
                </div>

                <div class="form-group">
                    <label for="email-address">الإيميل</label>
                    <input type="email" id="email-address" value="john.doe@gmail.com">
                </div>

                <button type="submit" class="edit-btn">تعديل</button>
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
