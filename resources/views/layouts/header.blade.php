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
                <li><a href="{{ route('home') }}" class="nav-link active">الرئيسية</a></li>
                <li><a href="{{ route('front.properties.index') }}" class="nav-link">العقارات</a></li>
                <li><a href="#" class="nav-link">المشاريع</a></li>
                <li><a href="{{ route('front.properties.create') }}" class="nav-link">إضافة عقار</a></li>
                <li><a href="{{ route('front.contact') }}" class="nav-link">تواصل معنا</a></li>
            </ul>
        </nav>
        <div class="header__actions">
            <a class="btn btn-login">تسجيل الدخول</a>
            <a class="btn btn-lang">English</a>
        </div>

    </div>
</header>

<aside class="sidebar-header" aria-hidden="true">
    <ul class="sidebar-menu">
        <li><a href="#" class="sidebar-link active">الرئيسية</a></li>
        <li><a href="#" class="sidebar-link">العقارات</a></li>
        <li><a href="#" class="sidebar-link">المشاريع</a></li>
        <li><a href="#" class="sidebar-link">إضافة عقار</a></li>
        <li><a href="#" class="sidebar-link">تواصل معنا</a></li>
        <li class="sidebar-actions-mobile">
            <a class="btn btn-login-mobile">تسجيل الدخول</a>
            <a class="btn btn-lang-mobile">English</a>
        </li>
    </ul>
</aside>
