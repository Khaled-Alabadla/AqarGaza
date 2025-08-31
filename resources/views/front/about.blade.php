@extends('layouts.front')

@section('title', 'من نحن')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/about_styles.css') }}">
@endpush

@section('content')
    <main class="main-content">

        <section class="about-us-content-section section-padding">
            <div class="container">
                <div class="about-block main-card">
                    <h2>مقدمة عن المشروع</h2>
                    <p>هذا الموقع هو نتاج عمل طلابي من <b>جامعة فلسطين</b>، تم تطويره كجزء من <b>مشروع التخرج</b> في قسم
                        هندسة البرمجيات لعام 2025 يهدف المشروع إلى تقديم حلول مبتكرة لسوق العقارات المحلي في قطاع غزة
                    </p>
                </div>


                <div class="about-block main-card">
                    <h2>رؤيتنا ورسالتنا</h2>
                    <h3>الرؤية:</h3>
                    <p>أن نصبح المنصة العقارية الرائدة والموثوقة في فلسطين، التي تمكن الأفراد من العثور على عقارات
                        أحلامهم بكل سهولة وشفافية، وتساهم في تنمية السوق العقاري الرقمي.</p>
                    <h3>الرسالة:</h3>
                    <p>تطوير نظام عقاري متكامل وعصري، يجمع بين الابتكار التكنولوجي والخبرة المحلية، لتقديم تجربة مستخدم
                        فريدة تخدم جميع أطراف العملية العقارية بكفاءة وفعالية.</p>
                </div>

                <div class="about-block main-card">
                    <h2>فريق العمل</h2>
                    <p>يتكون فريقنا من طلاب متخصصين ومتحمسين من جامعة فلسطين، عملوا بجد وتفانٍ لإنجاز هذا المشروع:</p>
                    <div class="team-grid">
                        <div class="team-member">
                            <img src="{{ asset('assets/img/khaled.jpg') }}" alt="صورة عضو الفريق 1" class="member-avatar">
                            <h4>خالد عصام العبادلة</h4>
                            <p class="member-role">قائد الفريق/مطور الواجهة الخلفية</p>
                            <p class="member-description">نبذة مختصرة عن اهتماماته أو خبرته في المشروع.</p>
                            <div class="member-social-links">
                                <a href="https://www.linkedin.com/khaled-alabadla" aria-label="LinkedIn"><i
                                        class="fab fa-linkedin-in"></i></a>
                                <a href="https://github.com/khaled-alabadla" aria-label="GitHub"><i
                                        class="fab fa-github"></i></a>
                            </div>
                        </div>
                        <div class="team-member">
                            <img src="{{ asset('assets/img/Ali.jpg') }}" alt="صورة عضو الفريق 1" class="member-avatar">
                            <h4> علي ممدوح عبد الغفور</h4>
                            <p class="member-role"> مطور الواجهة الأمامية</p>
                            <p class="member-description">نبذة مختصرة عن اهتماماته أو خبرته في المشروع.</p>
                            <div class="member-social-links">
                                <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                                <a href="#" aria-label="GitHub"><i class="fab fa-github"></i></a>
                            </div>
                        </div>

                        <div class="team-member">
                            <img src="{{ asset('assets/img/bilal-q.jpg') }}" alt="صورة عضو الفريق 1" class="member-avatar">
                            <h4> بلال سمير قنوع</h4>
                            <p class="member-role"> مطور الموبايل</p>
                            <p class="member-description">نبذة مختصرة عن اهتماماته أو خبرته في المشروع.</p>
                            <div class="member-social-links">
                                <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                                <a href="#" aria-label="GitHub"><i class="fab fa-github"></i></a>
                            </div>
                        </div>
                        <div class="team-member">
                            <img src="{{ asset('assets/img/bilal-s.jpg') }}" alt="صورة عضو الفريق 1" class="member-avatar">
                            <h4> بلال رضوان الشريف</h4>
                            <p class="member-role"> مصمم UI/UX </p>
                            <p class="member-description">نبذة مختصرة عن اهتماماته أو خبرته في المشروع.</p>
                            <div class="member-social-links">
                                <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                                <a href="#" aria-label="GitHub"><i class="fab fa-github"></i></a>
                            </div>
                        </div>
                        <div class="team-member">
                            <img src="{{ asset('assets/img/ahmed.jpg') }}" alt="صورة عضو الفريق 1" class="member-avatar">
                            <h4> أحمد عمار حرارة</h4>
                            <p class="member-role"> مساعد في تطوير الواجهة الأمامية</p>
                            <p class="member-description">نبذة مختصرة عن اهتماماته أو خبرته في المشروع.</p>
                            <div class="member-social-links">
                                <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                                <a href="#" aria-label="GitHub"><i class="fab fa-github"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="about-block main-card">
                    <h2>رحلة المشروع والتحديات</h2>
                    <p>بدأ مشروعنا بفكرة بسيطة: تبسيط عملية البحث عن العقارات في فلسطين. مررنا بمراحل متعددة من البحث
                        والتخطيط والتصميم والبرمجة، وواجهنا العديد من التحديات التقنية والتصميمية.</p>
                    <p>من أبرز التحديات التي تغلبنا عليها اذكر مثال على تحدي تقني أو تصميمي، مثل: بناء نظام خرائط
                        تفاعلي، أو تحقيق التجاوبية الكاملة. بفضل التعاون المستمر والدعم من أساتذتنا، تمكنا من تحويل هذه
                        التحديات إلى فرص للتعلم والابتكار.</p>
                    <h3>التقنيات المستخدمة:</h3>
                    <ul>
                        <li><b>الواجهة الأمامية (Frontend): </b> HTML5, CSS3, JavaScript.</li>
                        <li><b>الأطر والمكتبات (Frameworks/Libraries):</b> Vue.js, Bootstrap, TailwindCSS</li>
                        <li><b>الواجهة الخلفية (Backend): </b>PHP/Laravel.
                        </li>
                        <li><b>قواعد البيانات (Database): </b>MySQL.</li>
                    </ul>
                </div>

                <div class="about-block main-card">
                    <h2>قيمنا الأساسية</h2>
                    <p>نؤمن بمجموعة من القيم التي قادت عملنا في هذا المشروع:</p>
                    <ul class="values-list">
                        <li><i class="fas fa-check-circle"></i> <b>الابتكار:</b> نسعى لتقديم حلول تقنية جديدة ومبتكرة في
                            السوق العقاري.</li>
                        <li><i class="fas fa-check-circle"></i> <b>الشفافية:</b> نلتزم بعرض المعلومات بوضوح ونزاهة.</li>
                        <li><i class="fas fa-check-circle"></i> <b>سهولة الاستخدام:</b> نركز على تصميم واجهات بسيطة
                            وفعالة لجميع المستخدمين.</li>
                        <li><i class="fas fa-check-circle"></i> <b>خدمة المجتمع:</b> نأمل أن يساهم مشروعنا في تلبية
                            احتياجات المجتمع المحلي.</li>
                    </ul>
                </div>

                <div class="about-block main-card">
                    <h2>شكر وتقدير</h2>
                    <p>نتقدم بجزيل الشكر لـ <b>جامعة فلسطين</b> على توفير البيئة الأكاديمية الداعمة، و <b>لدكتورنا
                            المشرف عبد الحميد زغبر </b> على توجيهاته القيمة وخبرته التي كانت لا غنى عنها لإنجاح هذا
                        المشروع. كما نشكر كل من قدم لنا الدعم والتشجيع خلال هذه الرحلة.</p>
                </div>

                <div class="about-block main-card">
                    <h2>تواصل مع فريق المشروع</h2>
                    <p>إذا كان لديك أي أسئلة حول المشروع، يرجى التواصل معنا عبر النموذج التالي:</p>
                    <p>
                        <a href="{{ route('front.contact') }}">اضغط هنا</a>
                    </p>


                </div>
            </div>
        </section>
    </main>
@endsection
