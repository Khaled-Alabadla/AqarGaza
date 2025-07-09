<header class="site-header">
    <div class="container header__inner">
        <a class="sidebar-toggle" aria-label="قائمة" title="قائمة">
            <i class="fa-solid fa-bars"></i>
        </a>
        <a href="{{ route('home') }}" class="logo">
            <img src="{{ cache('settings')['site_logo'] ?? asset('assets/img/ho.png') }}" alt="شعار الموقع">
        </a>
        <nav class="main-nav">
            <ul>
                <li><a href="{{ route('home') }}"
                        class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">الرئيسية</a></li>
                <li><a href="{{ route('front.properties.index') }}"
                        class="nav-link {{ request()->routeIs('front.properties.index') ? 'active' : '' }}">العقارات</a>
                </li>
                <li><a href="{{ route('front.favorites.index') }}"
                        class="nav-link {{ request()->routeIs('front.favorites.index') ? 'active' : '' }}">المفضلة</a>
                </li>
                <li><a href="{{ route('front.properties.create') }}"
                        class="nav-link {{ request()->routeIs('front.properties.create') ? 'active' : '' }}">إضافة
                        عقار</a></li>
                <li><a href="{{ route('front.contact') }}"
                        class="nav-link {{ request()->routeIs('front.contact') ? 'active' : '' }}">تواصل معنا</a></li>
            </ul>
        </nav>
        @guest

            <div class="header__actions">
                <a class="btn btn-login" style="text-decoration:none" href="{{ route('login') }}">تسجيل الدخول</a>
                <a class="btn btn-lang" style="text-decoration:none" href="{{ route('register') }}">تسجيل</a>
            </div>
        @endguest
        @auth
            <div class="header__actions">
                <p class="btn btn-login" style="text-decoration:none; cursor: default;">مرحبا
                    {{ explode(' ', auth()->user()->name)[0] }}</p>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-lang" style="text-decoration:none" href="{{ route('register') }}">تسجيل
                        الخروج</button>
                </form>
            </div>
        @endauth

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
