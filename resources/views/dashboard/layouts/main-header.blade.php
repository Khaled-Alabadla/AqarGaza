<!-- main-header opened -->
<div class="main-header sticky side-header nav nav-item">
    <div class="container-fluid">
        <div class="main-header-left ">

            <div class="app-sidebar__toggle" data-toggle="sidebar">
                <a class="open-toggle" href="#"><i class="header-icon fe fe-align-left"></i></a>
                <a class="close-toggle" href="#"><i class="header-icons fe fe-x"></i></a>
            </div>

        </div>
        <div class="main-header-right">
            <div class="nav nav-item  navbar-nav-right ml-auto">
                <div class="dropdown nav-item main-header-notification">
                    <a class="new nav-link" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-bell">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        </svg>
                        {{-- @if (auth()->user()->unreadNotifications->count() > 0)
                            <span class=" pulse"></span>
                        @endif --}}
                    </a>
                    <div class="dropdown-menu">
                        <div class="menu-header-content bg-primary text-right">
                            <div class="d-flex">
                                <h6 class="dropdown-title mb-1 tx-15 text-white font-weight-semibold">الإشعارات</h6>
                                {{-- @if (auth()->user()->unreadNotifications->count() > 0)
                                    <a href="{{ route('notifications.read.all') }}"
                                        class="badge badge-pill badge-warning mr-auto my-auto float-left">تعيين الكل
                                        مقروء</a>
                                @endif --}}
                            </div>
                            <p class="dropdown-title-text subtext mb-0 text-white op-6 pb-0 tx-12 ">
                                {{-- {{ auth()->user()->unreadNotifications->count() == 0 ? 'لا يوجد' : auth()->user()->unreadNotifications->count() }} --}}
                                إشعارات</p>
                        </div>
                        {{-- @if (auth()->user()->unreadNotifications->count() > 0)
                            <div class="main-notification-list Notification-scroll">
                                @foreach (auth()->user()->unreadNotifications as $notification)
                                    <a class="d-flex p-3 border-bottom" href="#"
                                        onclick="event.preventDefault();
                document.getElementById('mark-as-read-{{ $notification->id }}').submit();">
                                        <i class="fas fa-atom"></i>
                                        <div class="mr-3">
                                            <h5 class="notification-label mb-1">{{ $notification->data['message'] }}
                                            </h5>
                                            <div class="notification-subtext">
                                                {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                            </div>
                                        </div>
                                        <div class="mr-auto">
                                            <i class="las la-angle-left text-left text-muted"></i>
                                        </div>
                                    </a>

                                    <form id="mark-as-read-{{ $notification->id }}"
                                        action="{{ route('notifications.read', $notification->id) }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        @method('PATCH')
                                    </form>
                                @endforeach


                            </div>
                        @endif --}}

                    </div>
                </div>
                <div class="nav-item full-screen fullscreen-button">
                    <a class="new nav-link full-screen-link" href="#"><svg xmlns="http://www.w3.org/2000/svg"
                            class="header-icon-svgs" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-maximize">
                            <path
                                d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3">
                            </path>
                        </svg></a>
                </div>
                <div class="dropdown main-profile-menu nav nav-item nav-link">
                    <a class="profile-user d-flex" href=""><img alt=""
                            src="{{ Auth::user()->image == null ? 'https://cdn-icons-png.freepik.com/512/2552/2552801.png?ga=GA1.1.1883128924.1735321250' : asset('uploads/' . Auth::user()->image) }}">
                    </a>
                    <div class="dropdown-menu">
                        <div class="main-header-profile bg-primary p-3">
                            <div class="d-flex wd-100p">
                                {{-- <div class="main-img-user"><img alt=""
                                        src="https://cdn-icons-png.freepik.com/512/2552/2552801.png?ga=GA1.1.1883128924.1735321250" class=""></div> --}}
                                <div class="mr-3 my-auto">
                                    <h6>{{ Auth::user()->name }}</h6><span>{{ Auth::user()->identity_number }}</span>
                                </div>
                            </div>
                        </div>
                        <a class="dropdown-item" href="{{ route('front.profile') }}">
                            <i class="bx bxs-inbox"></i>

                            تعديل الملف الشخصي
                        </a>
                        <a class="dropdown-item" href="{{ route('front.auth.edit_password') }}">
                            <i class="bx bx-cog"></i>

                            تعديل كلمة المرور
                        </a>
                        <a class="dropdown-item" href="{{ url('/' . ($page = 'page-signin')) }}"
                            onclick="event.preventDefault(); document.querySelector('#logout-form').submit()"><i
                                class="bx bx-log-out"></i> تسجيل الخروج</a>
                        <form action="{{ route('logout') }}" id="logout-form" method="post" style="display: none">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /main-header -->
