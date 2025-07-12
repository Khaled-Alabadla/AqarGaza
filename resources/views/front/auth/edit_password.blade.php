@extends('layouts.front')

@section('title', 'تغيير كلمة المرور')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/edit_password_styles.css') }}">
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
        <div class="password-form-column">
            <section class="password-form-section">
                <h1 class="main-heading">تغيير كلمة المرور</h1>
                <p class="description">دعونا نجعلكم حديثاً ومساعدين حتى تتمكنوا من الوصول إلى حسابكم الشخصي</p>

                <form id="changePasswordForm" action="{{ route('front.auth.edit_password') }}" method="POST">
                    @csrf
                    <div class="form-group password-group">
                        <label for="old-password">كلمة السر القديمة</label>
                        <input type="password" name="password" id="old-password" placeholder="***********">
                        <i class="far fa-eye toggle-password" data-target="old-password"></i>
                        @error('password')
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group password-group">
                        <label for="new-password">كلمة السر الجديدة</label>
                        <input type="password" id="new-password" placeholder="***********" name="new_password">
                        <i class="far fa-eye toggle-password" data-target="new-password"></i>
                        @error('new_password')
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group password-group">
                        <label for="confirm-password">تأكيد كلمة السر</label>
                        <input type="password" name="new_password_confirmation" id="confirm-password"
                            placeholder="***********">
                        <i class="far fa-eye toggle-password" data-target="confirm-password"></i>
                    </div>

                    <button type="submit" class="submit-btn">تغيير كلمة المرور</button>
                </form>

                <div class="password-tips">
                    <ul>
                        <li><i class="fas fa-check"></i> قم بالتأكد من وضع كلمة مرور قوية</li>
                        <li><i class="fas fa-check"></i> قم بالتأكد من حفظ كلمة المرور </li>
                        <li><i class="fas fa-check"></i> لا تتردد في التواصل معنا في حال حصول أي خلل </li>
                    </ul>
                </div>
            </section>
        </div>

    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/edit_password.js') }}"></script>
@endpush
