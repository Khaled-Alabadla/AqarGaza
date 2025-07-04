@extends('layouts.front')

@section('title', 'نسيت كلمة المرور')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/forget_styles.css') }}">
@endpush

@section('content')
    <div class="forget">

        <div class="forgot-password-container">
            <div class="forgot-password-card">
                @if ($status)
                    <div style="color: rgb(59, 150, 59); margin-bottom: 10px; font-weight:600">
                        {{ $status }}
                    </div>
                @endif
                <div class="lock-icon-wrapper">
                    <i class="fas fa-lock"></i>
                </div>
                <h1 class="title">نسيت كلمة المرور؟</h1>
                <p class="description">أدخل بريدك الإلكتروني لتلقي رابط إعادة تعيين كلمة المرور</p>


                <form id="forgotPasswordForm" class="forgot-password-form" action="{{ route('password.email') }}"
                    method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="email">البريد الإلكتروني</label>
                        <input type="email" id="email" name="email" placeholder="email@example.com" required>
                    </div>
                    <button type="submit" class="submit-btn">رابط إعادة تعيين كلمة المرور</button>
                </form>
                <span class="back-to-login"> أو قم بالرجوع إلى <a href="{{ route('login') }}"> تسجيل الدخول </a> </span>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // **    'active' لروابط التنقل الرئيسية (main-nav) **

            const navLinks = document.querySelectorAll(".main-nav .nav-link");

            navLinks.forEach((link) => {
                link.addEventListener("click", (event) => {
                    // منع السلوك الافتراضي للرابط (الانتقال إلى صفحة أخرى) مؤقتًا
                    // يمكنك إزالة هذا السطر إذا كنت تريد أن ينقلك الرابط لصفحة فعلية مع تغيير الـ active
                    event.preventDefault();

                    // 1. البحث عن الرابط النشط حاليًا في القائمة الرئيسية وإزالة فئة 'active' منه
                    const currentActiveNavLink = document.querySelector(
                        ".main-nav .nav-link.active"
                    );
                    if (currentActiveNavLink) {
                        currentActiveNavLink.classList.remove("active");
                    }

                    // 2. إضافة فئة 'active' إلى الرابط الذي تم النقر عليه في القائمة الرئيسية
                    event.currentTarget.classList.add("active");
                });
            });

            // ** وظيفة تبديل الفئة 'active' لروابط القائمة الجانبية (sidebar-menu) **

            const sidebarLinks = document.querySelectorAll(".sidebar-menu .sidebar-link");

            sidebarLinks.forEach((link) => {
                link.addEventListener("click", (event) => {
                    // منع السلوك الافتراضي للرابط (الانتقال إلى صفحة أخرى) مؤقتًا
                    // يمكنك إزالة هذا السطر إذا كنت تريد أن ينقلك الرابط لصفحة فعلية مع تغيير الـ active
                    event.preventDefault();

                    // 1. البحث عن الرابط النشط حاليًا في القائمة الجانبية وإزالة فئة 'active' منه
                    const currentActiveSidebarLink = document.querySelector(
                        ".sidebar-menu .sidebar-link.active"
                    );
                    if (currentActiveSidebarLink) {
                        currentActiveSidebarLink.classList.remove("active");
                    }

                    // 2. إضافة فئة 'active' إلى الرابط الذي تم النقر عليه في القائمة الجانبية
                    event.currentTarget.classList.add("active");
                });
            });

            // ** وظيفة تبديل القائمة الجانبية (Sidebar) **

            const toggle = document.querySelector(".sidebar-toggle");
            const sidebar_header = document.querySelector(".sidebar-header");

            toggle.addEventListener("click", (e) => {
                e.preventDefault();
                sidebar_header.classList.toggle("open");
                sidebar_header.setAttribute(
                    "aria-hidden",
                    sidebar_header.classList.contains("open") ? "false" : "true"
                );

                // بدّل الأيقونة
                const icon = toggle.querySelector("i");
                if (sidebar_header.classList.contains("open")) {
                    icon.classList.replace("fa-bars", "fa-xmark");
                } else {
                    icon.classList.replace("fa-xmark", "fa-bars");
                }
            });


            // ** تحديث السنة في الفوتر تلقائياً **

            const currentYearSpan = document.getElementById("current-year");
            if (currentYearSpan) {
                // تحقق لتجنب الأخطاء إذا لم يكن العنصر موجودًا
                currentYearSpan.textContent = new Date().getFullYear();
            }
        });
    </script>
@endpush
