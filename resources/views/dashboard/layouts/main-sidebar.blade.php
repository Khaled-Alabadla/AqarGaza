<!-- main-sidebar -->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar sidebar-scroll">
    <div class="main-sidebar-header active">
        <a class="desktop-logo logo-light active" style="height: 94%" href="{{ route('dashboard.dashboard', 'home') }}"><img
                style="height: 100%" src="{{ asset('assets/img/logo.jpg') }}" class="main-logo" alt="logo"></a>
        <a class="desktop-logo logo-dark active"href="{{ route('dashboard.dashboard', 'home') }}"><img
                src="{{ asset('assets/img/logo.jpg') }}" class="main-logo dark-theme" alt="logo"></a>
        <a class="logo-icon mobile-logo icon-light active" href="{{ route('dashboard.dashboard', 'home') }}"><img
                src="{{ asset('assets/img/logo.jpg') }}" class="logo-icon" alt="logo"></a>
        <a class="logo-icon mobile-logo icon-dark active"href="{{ route('dashboard.dashboard', 'home') }}"><img
                src="{{ asset('assets/img/logo.jpg') }}" class="logo-icon dark-theme" alt="logo"></a>
    </div>
    <div class="main-sidemenu">
        <div class="app-sidebar__user clearfix">
            <div class="dropdown user-pro-body">
                <div class="">
                    <img alt="user-img" class="avatar avatar-xl brround"
                        src="https://cdn-icons-png.freepik.com/512/2552/2552801.png?ga=GA1.1.1883128924.1735321250"><span
                        class="avatar-status profile-status bg-green"></span>
                </div>
                <div class="user-info">
                    <h4 class="font-weight-semibold mt-3 mb-0">{{ Auth::user()->name }}</h4>
                    {{-- <span class="mb-0 text-muted">{{ Auth::user()->identity_number }}</span> --}}

                </div>
            </div>
        </div>
        <ul class="side-menu">
            @can('home')
                <li class="side-item side-item-category">الرئيسية</li>
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('dashboard.dashboard', 'home') }}"><svg
                            xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
                            <path d="M0 0h24v24H0V0z" fill="none" />
                            <path d="M5 5h4v6H5zm10 8h4v6h-4zM5 17h4v2H5zM15 5h4v2h-4z" opacity=".3" />
                            <path
                                d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z" />
                        </svg><span class="side-menu__label">الرئيسية</span></a>
                </li>
            @endcan

            @can('users.display')
                <li class="side-item side-item-category">المستخدمين</li>
                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . ($page = '#')) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24"
                            fill="currentColor">
                            <path
                                d="M12 12c2.67 0 5 2.33 5 5v1h-10v-1c0-2.67 2.33-5 5-5zm0-2c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zM4 20c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-1c0-4.41-3.59-8-8-8H8c-4.41 0-8 3.59-8 8v1zm2-5c-2.21 0-4 1.79-4 4v1h4v-1c0-1.1.9-2 2-2h2c1.1 0 2 .9 2 2v1h4v-1c0-2.21-1.79-4-4-4h-6z" />
                        </svg>
                        <span class="side-menu__label">المستخدمين</span><i class="angle fe fe-chevron-down"></i></a>
                    <ul class="slide-menu">
                        @can('users.index')
                            <li><a class="slide-item" href="{{ route('dashboard.users.index') }}">عرض قائمة المستخدمين</a></li>
                        @endcan

                        @can('users.create')
                            <li><a class="slide-item" href="{{ route('dashboard.users.create') }}">إضافة مستخدم</a></li>
                        @endcan

                        {{-- @can('users.trash')
                            <li><a class="slide-item" href="{{ route('dashboard.users.trash') }}">المستخدمين المحذوفين</a></li>
                        @endcan --}}
                    </ul>
                </li>
            @endcan

            @can('roles.display')
                <li class="side-item side-item-category">الصلاحيات</li>
                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . ($page = '#')) }}">
                        <i class="fa fa-lock" aria-hidden="true"></i>



                        <span class="side-menu__label">الصلاحيات</span><i class="angle fe fe-chevron-down"></i></a>
                    <ul class="slide-menu">

                        @can('roles.index')
                            <li><a class="slide-item" href="{{ route('dashboard.roles.index') }}">عرض قائمة الصلاحيات</a></li>
                        @endcan

                        @can('roles.create')
                            <li><a class="slide-item" href="{{ route('dashboard.roles.create') }}">إضافة صلاحية جديدة</a></li>
                        @endcan

                        @can('roles.users.index')
                            <li><a class="slide-item" href="{{ route('dashboard.users.roles.index') }}">صلاحيات المستخدمين</a></li>
                        @endcan
                    </ul>
                </li>
            @endcan

            {{-- <li class="side-item side-item-category">المسؤولين</li> --}}

            {{-- <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . ($page = '#')) }}"><svg
                        xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path d="M4 12c0 4.08 3.06 7.44 7 7.93V4.07C7.05 4.56 4 7.92 4 12z" opacity=".3" />
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.94-.49-7-3.85-7-7.93s3.05-7.44 7-7.93v15.86zm2-15.86c1.03.13 2 .45 2.87.93H13v-.93zM13 7h5.24c.25.31.48.65.68 1H13V7zm0 3h6.74c.08.33.15.66.19 1H13v-1zm0 9.93V19h2.87c-.87.48-1.84.8-2.87.93zM18.24 17H13v-1h5.92c-.2.35-.43.69-.68 1zm1.5-3H13v-1h6.93c-.04.34-.11.67-.19 1z" />
                    </svg><span class="side-menu__label">المسؤولين</span><i class="angle fe fe-chevron-down"></i></a>
                <ul class="slide-menu">
                    <li><a class="slide-item" href="{{ route('admins.index') }}"> عرض قائمة المسؤولين</a></li>
                    <li><a class="slide-item" href="{{ route('admins.create') }}"> إضافة مسؤول</a></li>
                </ul>
            </li>  --}}


            {{-- @can('donors.display')
                <li class="side-item side-item-category">الجهات المانحة</li>
                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . ($page = '#')) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
                            <path d="M0 0h24v24H0V0z" fill="none" />
                            <path d="M4 12c0 4.08 3.06 7.44 7 7.93V4.07C7.05 4.56 4 7.92 4 12z" opacity=".3" />
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.94-.49-7-3.85-7-7.93s3.05-7.44 7-7.93v15.86zm2-15.86c1.03.13 2 .45 2.87.93H13v-.93zM13 7h5.24c.25.31.48.65.68 1H13V7zm0 3h6.74c.08.33.15.66.19 1H13v-1zm0 9.93V19h2.87c-.87.48-1.84.8-2.87.93zM18.24 17H13v-1h5.92c-.2.35-.43.69-.68 1zm1.5-3H13v-1h6.93c-.04.34-.11.67-.19 1z" />
                        </svg>
                        <span class="side-menu__label">الجهات المانحة</span><i class="angle fe fe-chevron-down"></i></a>
                    <ul class="slide-menu">

                        @can('donors.index')
                            <li><a class="slide-item" href="{{ route('dashboard.donors.index') }}">عرض قائمة الجهات المانحة</a>
                            </li>
                        @endcan

                        @can('donors.create')
                            <li><a class="slide-item" href="{{ route('dashboard.donors.create') }}">إضافة جهة مانحة</a> </li>
                        @endcan

                        @can('donors.trash')
                            <li><a class="slide-item" href="{{ route('dashboard.donors.trash') }}">الجهات المانحة المحذوفة</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan --}}

            @can('properties.display')
            <li class="side-item side-item-category">المساعدات</li>
            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . ($page = '#')) }}">
                    <i class="fas fa-hands-helping"></i>
                    <span class="side-menu__label">المساعدات</span><i class="angle fe fe-chevron-down"></i></a>
                <ul class="slide-menu">

                    @can('properties.index')
                        <li><a class="slide-item" href="{{ route('dashboard.properties.index') }}">المساعدات الإجمالية</a></li>
                    @endcan

                    @can('properties.user')
                        <li><a class="slide-item" href="{{ route('dashboard.properties.user', Auth::user()->id) }}">المساعدات
                                المستلمة</a></li>
                    @endcan

                    @can('properties.create')
                        <li><a class="slide-item" href="{{ route('dashboard.properties.create') }}">إضافة مساعدة جديدة</a></li>
                    @endcan

                    @can('properties.trash')
                        <li><a class="slide-item" href="{{ route('dashboard.properties.trash') }}">المساعدات المحذوفة</a></li>
                    @endcan
                </ul>
            </li>
        @endcan


            {{-- @can('queries.display')
                <li class="side-item side-item-category">الاستعلامات والتقارير</li>
                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . ($page = '#')) }}">
                        <i class="fa-solid fa-question"></i>



                        <span class="side-menu__label">الاستعلامات والتقارير</span><i
                            class="angle fe fe-chevron-down"></i></a>
                    <ul class="slide-menu">

                        @can('queries.users')
                            <li><a class="slide-item" href="{{ route('dashboard.queries.users') }}">المستخدمين</a>
                            @endcan

                            @can('queries.donors')
                            <li><a class="slide-item" href="{{ route('dashboard.queries.donors') }}">الجهات المانحة</a>
                            </li>
                        @endcan

                </li>
            </ul>
            </li>
        @endcan --}}

        @can('settings.display')
            <li class="side-item side-item-category">الإعدادات</li>
            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . ($page = '#')) }}">
                    <i class="fa fa-cog" aria-hidden="true"></i>
                    <span class="side-menu__label">الإعدادات</span><i class="angle fe fe-chevron-down"></i></a>
                <ul class="slide-menu">

                    @can('users.update_profile')
                        <li><a class="slide-item" href="{{ route('dashboard.users.edit') }}">تعديل الملف
                                الشخصي</a></li>
                    @endcan

                    @can('users.reset_password')
                        <li><a class="slide-item" href="{{ route('dashboard.users.reset_password') }}">تغيير كلمة
                                المرور</a></li>
                    @endcan
                </ul>
            </li>
        @endcan
        </ul>
    </div>
</aside>
<!-- main-sidebar -->
