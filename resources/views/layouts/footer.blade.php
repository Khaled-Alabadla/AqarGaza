<footer class="site-footer">
    <div class="footer-top">
        <div class="container footer-top__inner">
            <div class="footer-newsletter">
                <img src="{{ app('settings')['site_logo'] ? asset(app('settings')['site_logo']) : asset('assets/img/ho.png') }}"
                    alt="شعار الموقع في الفوتر" class="footer-logo-img">
                <p class="footer-newsletter__follow">ابق على اطلاع ومتابعة</p>
                <div class="social-links">
                    @if (app('settings')['linkedin'] ?? '')
                        <a href="{{ app('settings')['linkedin'] ?? '' }}" aria-label="LinkedIn"><i
                                class="fa-brands fa-linkedin-in"></i>
                        </a>
                    @endif

                    @if (app('settings')['facebook'])
                        <a href="{{ app('settings')['facebook'] }}" aria-label="Facebook"><i
                                class="fa-brands fa-facebook-f"></i></a>
                    @endif

                    @if (app('settings')['twitter'])
                        <a href="{{ app('settings')['twitter'] }}" aria-label="Twitter"><i
                                class="fa-brands fa-twitter"></i></a>
                    @endif
                </div>
            </div>

            <div class="footer-links">
                <div class="footer-column">
                    <h4>استكشف عقاراتنا</h4>
                    <ul>
                        <li><a href="{{ route('front.properties.index', ['city' => 1]) }}">غزة</a></li>
                        <li><a href="{{ route('front.properties.index', ['city' => 2]) }}">خانيونس</a></li>
                        <li><a href="{{ route('front.properties.index', ['city' => 3]) }}">رفح</a></li>
                        <li><a href="{{ route('front.properties.index', ['city' => 4]) }}">الوسطى</a></li>
                        <li><a href="{{ route('front.properties.index', ['city' => 5]) }}">الشمال</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4>روابط سريعة</h4>
                    <ul>
                        <li><a href="{{ route('home') }}">الصفحة الرئيسية</a></li>
                        <li><a href="{{ route('front.about') }}">عنّا</a></li>
                        <li><a href="{{ route('front.properties.index') }}">قائمة العقارات</a></li>
                        <li><a href="{{ route('front.blog.index') }}">مدونتنا</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-contact">
                <h4>تواصل معنا</h4>
                <address>
                    <p><i class="fa-solid fa-location-dot"></i> <strong>الموقع:</strong> غزة - خانيونس<br> الشجاعية
                        - البريج</p>
                </address>
                <p><i class="fa-solid fa-phone-volume"></i> <strong>الهاتف:</strong>+970-592-431798</p>
                <p><i class="fa-solid fa-envelope"></i> <strong>البريد الإلكتروني:</strong>aqar-gaza@gmail.com</p>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container footer-bottom__inner">
            <div class="footer-bottom__links">
                <a href="{{ route('front.responsibility') }}">إخلاء المسؤولية</a>
                <a href="{{ route('front.privacy') }}">سياسة الخصوصية</a>
                <a href="{{ route('front.conditinos') }}">الشروط والأحكام</a>
            </div>
            @if (app('settings')['copyright'])
                <div class="footer-bottom__copy">
                    {{ app('settings')['copyright'] }}
                </div>
            @endif
        </div>
    </div>
</footer>
