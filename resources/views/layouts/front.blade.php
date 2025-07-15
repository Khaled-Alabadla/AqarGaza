<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', cache('settings')['site_name'] ?? 'عقارات غزة') </title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/all.min.css') }}" />
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/chat_styles.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('assets/css/favorites_styles.css') }}">
    @stack('styles')
</head>

<body>


    @include('layouts.header')

    @yield('content')

    @include('layouts.footer')

    <!-- جافاسكربت لتفعيل القائمة الجانبية -->

    <script>
        const userId = "{{ Auth::id() }}";
    </script>
    <script src="{{ asset('assets/js/simple_chat.js') }}"></script>

    <script class="side&active">
        document.addEventListener('DOMContentLoaded', () => {

            const toggle = document.querySelector('.sidebar-toggle');
            const sidebar_header = document.querySelector('.sidebar-header');

            toggle.addEventListener('click', (e) => {
                e.preventDefault();
                sidebar_header.classList.toggle('open');
                sidebar_header.setAttribute('aria-hidden', sidebar_header.classList.contains('open') ?
                    'false' : 'true');

                // بدّل الأيقونة
                const icon = toggle.querySelector('i');
                if (sidebar_header.classList.contains('open')) {
                    icon.classList.replace('fa-bars', 'fa-xmark');
                } else {
                    icon.classList.replace('fa-xmark', 'fa-bars');
                }
            });

            // ** تحديث السنة في الفوتر تلقائياً **



            const sidebarProfileToggle = document.querySelector('.sidebar-profile-toggle');
            const sidebarProfileDropdownWrapper = document.querySelector('.sidebar-profile-dropdown-wrapper');

            if (sidebarProfileToggle && sidebarProfileDropdownWrapper) {
                sidebarProfileToggle.addEventListener('click', (event) => {
                    event.preventDefault();
                    event.stopPropagation();

                    document.querySelectorAll('.sidebar-dropdown-wrapper.active').forEach(openWrapper => {
                        if (openWrapper !== sidebarProfileDropdownWrapper) {
                            openWrapper.classList.remove('active');
                        }
                    });

                    sidebarProfileDropdownWrapper.classList.toggle('active');
                });

                // إغلاق القائمة الفرعية عند النقر في أي مكان آخر في الصفحة
                document.addEventListener('click', (event) => {
                    if (sidebarProfileDropdownWrapper.classList.contains('active')) {
                        if (!sidebarProfileDropdownWrapper.contains(event.target)) {
                            sidebarProfileDropdownWrapper.classList.remove('active');
                        }
                    }
                });
            }

            // profile

            const profileIconContainer = document.querySelector('.profile-icon-container');
            const dropdownMenu = document.querySelector('.dropdown-menu');

            if (profileIconContainer && dropdownMenu) {
                profileIconContainer.addEventListener('click', (event) => {
                    event.stopPropagation();
                    profileIconContainer.classList.toggle('active');
                });

                document.addEventListener('click', (event) => {
                    if (profileIconContainer.classList.contains('active')) {
                        if (!profileIconContainer.contains(event.target)) {
                            profileIconContainer.classList.remove('active');
                        }
                    }
                });
            }

        });
    </script>


    {{-- <script src="{{ asset('assets/js/favorites.js') }}"></script> --}}
    <script src="{{ asset('assets/js/favorite.js') }}"></script>

    @stack('scripts')

</body>

</html>
