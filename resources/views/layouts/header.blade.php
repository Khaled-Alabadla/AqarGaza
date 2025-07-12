<header class="site-header">
    <div class="container header__inner">
        <a class="sidebar-toggle" aria-label="قائمة" title="قائمة">
            <i class="fa-solid fa-bars"></i>
        </a>
        <a href="#" class="logo">
            <img src="{{ asset('assets/img/ho.png') }}" alt="شعار الموقع">
        </a>
        <nav class="main-nav">
            <ul>
                <li><a href="{{ route('home') }}"
                        class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">الرئيسية</a></li>
                <li><a href="{{ route('front.properties.index') }}"
                        class="nav-link {{ request()->routeIs('front.properties.index') ? 'active' : '' }}">العقارات</a>
                </li>
                <li><a href="{{ route('front.favorites.index') }}"
                        class="nav-link {{ request()->routeIs('front.favorites.index') ? 'active' : '' }}">المشاريع</a>
                </li>
                <li><a href="{{ route('front.properties.create') }}"
                        class="nav-link {{ request()->routeIs('front.properties.create') ? 'active' : '' }}">إضافة
                        عقار</a></li>
                <li><a href="{{ route('front.contact') }}"
                        class="nav-link {{ request()->routeIs('front.contact') ? 'active' : '' }}">تواصل معنا</a></li>
            </ul>
        </nav>
        <div class="header__actions">
            @guest
                <a class="btn btn-login" style="text-decoration: none" href="{{ route('login') }}">تسجيل الدخول</a>
            @endguest

            @auth
                <div class="profile-icon-container">
                    <div class="profile-icon">
                        <img src="{{ asset(Auth::user()->image) }}" alt="الملف الشخصي">
                    </div>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('front.profile') }}"><i class="fas fa-user"></i> الملف الشخصي</a></li>
                        <li><a href="{{ route('front.favorites.index') }}"><i class="fas fa-heart"></i> المفضلة</a></li>
                        <li><a href="{{ route('front.auth.edit_password') }}"><i class="fas fa-address-book"></i>تغيير كلمة
                                المرور</a></li>
                        <li class="separator"></li>
                        <li><a href="#"
                                onclick="event.preventDefault();document.querySelector('#logout-form').submit();"><i
                                    class="fas fa-sign-out-alt"></i> تسجيل
                                الخروج</a></li>
                        <form id="logout-form" style="display: none" action="{{ route('logout') }}" method="POST">
                            @csrf
                        </form>
                    </ul>
                </div>
            @endauth
        </div>



    </div>
</header>

<aside class="sidebar-header" aria-hidden="true">
    <ul class="sidebar-menu">
        <li><a href="{{ route('home') }}"
                class="sidebar-link {{ request()->routeIs('home') ? 'active' : '' }}">الرئيسية</a></li>
        <li><a href="{{ route('front.properties.index') }}"
                class="sidebar-link {{ request()->routeIs('front.properties.index') ? 'active' : '' }}">العقارات</a>
        </li>
        <li><a href="{{ route('front.favorites.index') }}"
                class="sidebar-link {{ request()->routeIs('front.favorites.index') ? 'active' : '' }}">المفضلة</a>
        </li>
        <li><a href="{{ route('front.properties.create') }}"
                class="sidebar-link {{ request()->routeIs('front.properties.create') ? 'active' : '' }}">إضافة عقار</a>
        </li>
        <li><a href="{{ route('front.contact') }}"
                class="sidebar-link {{ request()->routeIs('front.contact') ? 'active' : '' }}">تواصل معنا</a></li>
        @guest
            <li class="sidebar-actions-mobile">
                <a class="btn btn-login-mobile" style="text-decoration:none" href="{{ route('login') }}">تسجيل الدخول</a>
                <a class="btn btn-lang-mobile" style="text-decoration:none" href="{{ route('register') }}">تسجيل</a>
            </li>
        @endguest
        @auth
            <li class="sidebar-actions-mobile">
                <p class="btn btn-login-mobile" style="text-decoration:none; cursor: default;" href="{{ route('login') }}"
                    style="">
                    مرحبا
                    {{ explode(' ', auth()->user()->name)[0] }}</p>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-lang-mobile logout-btn">تسجيل الخروج</button>
                </form>
            </li>
        @endauth
    </ul>
</aside>
