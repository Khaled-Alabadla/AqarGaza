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
        const simpleChatFab = document.querySelector('.chat-fab-simple');
        const simpleChatSidebar = document.querySelector('.chat-sidebar-simple');
        const simpleChatCloseBtn = document.querySelector('.chat-close-btn-simple');
        const simpleChatListUl = document.querySelector('.chat-list-simple');
        const allChatsSimpleBtn = document.querySelector('.all-chats-btn-simple');

        const simpleDummyChats = [{
                id: 1,
                name: 'أحمد محمود',
                lastMessage: 'تمام، أراك غداً.',
                time: 'الآن',
                avatar: "{{ asset('assets/img/landing.jpg') }}"
            },
            {
                id: 2,
                name: 'فاطمة علي',
                lastMessage: 'هل العقار لا يزال متاحاً؟',
                time: '10:30 ص',
                avatar: "{{ asset('assets/img/landing.jpg') }}"
            },
            {
                id: 3,
                name: 'مكتب الريان',
                lastMessage: 'سنرسل لك التفاصيل.',
                time: 'أمس',
                avatar: "{{ asset('assets/img/landing.jpg') }}"
            }
        ];

        function populateSimpleChatList() {
            simpleChatListUl.innerHTML = '';
            simpleDummyChats.forEach(chat => {
                const li = document.createElement('li');
                li.classList.add('chat-item-simple');
                li.dataset.chatId = chat.id; // لإمكانية إضافة وظيفة النقر لاحقاً
                li.innerHTML = `
                    <img src="${chat.avatar}" alt="${chat.name}" class="chat-avatar-simple">
                    <div class="chat-info-simple">
                        <span class="chat-name-simple">${chat.name}</span>
                        <p class="last-message-simple">${chat.lastMessage}</p>
                    </div>
                    <span class="chat-time-simple">${chat.time}</span>
                `;
                simpleChatListUl.appendChild(li);
            });
        }

        function toggleSimpleChatSidebar() {
            if (simpleChatSidebar.classList.contains('open')) {
                simpleChatSidebar.classList.remove('open');
                simpleChatSidebar.setAttribute('aria-hidden', 'true');
            } else {
                populateSimpleChatList();
                simpleChatSidebar.classList.add('open');
                simpleChatSidebar.setAttribute('aria-hidden', 'false');
            }
        }

        simpleChatFab.addEventListener('click', toggleSimpleChatSidebar);

        simpleChatCloseBtn.addEventListener('click', toggleSimpleChatSidebar);

        // مستمع حدث للنقر على "كل الدردشات" (سينتقل لصفحة Chats.html)
        allChatsSimpleBtn.addEventListener('click', (e) => {
            // e.preventDefault(); // ألغِ التعليق إذا كنت تريد معالجة النقر هنا فقط بدلاً من الانتقال الفعلي
            alert('سيتم نقلك إلى صفحة كل الدردشات.');
            // window.location.href = 'chats.html'; // للتنقل الفعلي
            toggleSimpleChatSidebar(); // إغلاق القائمة بعد النقر
        });

        document.addEventListener('click', (e) => {
            const isClickInsideFab = simpleChatFab.contains(e.target);
            const isClickInsideSidebar = simpleChatSidebar.contains(e.target);

            if (!isClickInsideFab && !isClickInsideSidebar && simpleChatSidebar.classList.contains('open')) {
                toggleSimpleChatSidebar();
            }
        });
    </script>

    <script>
        const toggle = document.querySelector('.sidebar-toggle');
        const sidebar_header = document.querySelector('.sidebar-header'); // تأكد أن هذا هو السلكتور الصحيح للـ aside

        toggle.addEventListener('click', (e) => {
            e.preventDefault();
            sidebar_header.classList.toggle('open');
            sidebar_header.setAttribute('aria-hidden', sidebar_header.classList.contains('open') ? 'false' :
                'true');

            // بدّل الأيقونة
            const icon = toggle.querySelector('i');
            if (sidebar_header.classList.contains('open')) {
                icon.classList.replace('fa-bars', 'fa-xmark'); // استخدم fa-xmark بدلاً من fa-x
            } else {
                icon.classList.replace('fa-xmark', 'fa-bars');
            }
        });

        // تحديث السنة في الفوتر تلقائياً
        document.getElementById('current-year').textContent = new Date().getFullYear();
    </script>

    <script class="fav">
        document.addEventListener('DOMContentLoaded', () => {
            const fabFavorites = document.querySelector('.fab-favorites');
            const favoritesSidebar = document.querySelector('.favorites-sidebar');
            const favoritesCloseBtn = document.querySelector('.favorites-close-btn');
            const favoritesListUl = document.querySelector('.favorites-list');
            const allFavoritesBtn = document.querySelector('.all-favorites-btn');

            const dummyFavorites = [{
                    id: 'property-card-1',
                    image: '../images/landing.jpg',
                    title: 'مسكن عائلي بحديقة',
                    details: 'الرياض، الملك فهد | $96.000'
                },
                {
                    id: 'listing-card-3',
                    image: '../images/landing.jpg',
                    title: 'شقة فاخرة للإيجار',
                    details: 'حي الورود، الرياض | 2,200 ريال'
                },
                {
                    id: 'property-card-2',
                    image: '../images/landing.jpg',
                    title: 'فيلا حديثة للبيع',
                    details: 'جدة، حي المروج | $205.000'
                }
            ];

            function populateFavoritesList() {
                favoritesListUl.innerHTML = '';
                if (dummyFavorites.length === 0) {
                    favoritesListUl.innerHTML =
                        '<li class="favorites-empty-message" style="text-align: center; padding: 20px; color: var(--text-color-light);">لا توجد عقارات في المفضلة بعد.</li>';
                    return;
                }
                dummyFavorites.forEach(fav => {
                    const li = document.createElement('li');
                    li.classList.add('favorites-item');
                    li.dataset.propertyId = fav.id;
                    li.innerHTML = `
                <img src="${fav.image}" alt="${fav.title}" class="favorites-item-image">
                <div class="favorites-item-info">
                    <span class="favorites-item-title">${fav.title}</span>
                    <p class="favorites-item-details">${fav.details}</p>
                </div>
            `;
                    favoritesListUl.appendChild(li);
                });

                document.querySelectorAll('.favorites-item').forEach(item => {
                    item.addEventListener('click', () => {
                        const propertyId = item.dataset.propertyId;
                        const targetCard = document.getElementById(
                            propertyId); // البحث عن البطاقة بالـ ID
                        if (targetCard) {
                            targetCard.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            }); // التمرير بسلاسة
                            toggleFavoritesSidebar(); // إغلاق القائمة بعد التمرير
                        } else {
                            // إذا لم يتم العثور على البطاقة، يمكن توجيه المستخدم لصفحة تفاصيل العقار
                            // window.location.href = `property-details.html?id=${propertyId}`;
                            alert(
                                `لم يتم العثور على بطاقة العقار رقم ${propertyId} في الصفحة الحالية.`
                            );
                            toggleFavoritesSidebar();
                        }
                    });
                });
            }

            function toggleFavoritesSidebar() {
                // إذا كان لديك sidebar عام أو قائمة دردشة من أكواد JS أخرى،
                // يجب عليك إضافة منطق لإغلاقها هنا لتجنب التداخل.
                // بما أن هذا كود منفصل، فإنه لن يتمكن من الوصول المباشر للمتغيرات الأخرى
                // إلا إذا تم تمريرها كمعاملات أو جعلها عامة (Global) أو إعادة تحديدها.
                // للتكامل الكامل، يفضل دمج كل أكواد Sidebars في ملف JS واحد.
                // مثال (إذا كانت المتغيرات الأخرى Global أو قمت بإعادة تحديدها):
                // if (typeof sidebar_header !== 'undefined' && sidebar_header.classList.contains('open')) { /* ... إغلاق ... */ }
                // if (typeof simpleChatSidebar !== 'undefined' && simpleChatSidebar.classList.contains('open')) { /* ... إغلاق ... */ }

                if (favoritesSidebar.classList.contains('open')) {
                    favoritesSidebar.classList.remove('open');
                    favoritesSidebar.setAttribute('aria-hidden', 'true');
                } else {
                    populateFavoritesList(); // ملء القائمة قبل الفتح
                    favoritesSidebar.classList.add('open');
                    favoritesSidebar.setAttribute('aria-hidden', 'false');
                }
            }

            fabFavorites.addEventListener('click', toggleFavoritesSidebar);

            favoritesCloseBtn.addEventListener('click', toggleFavoritesSidebar);

            allFavoritesBtn.addEventListener('click', (e) => {
                e.preventDefault();
                alert('سيتم نقلك إلى صفحة كل المفضلة.');
                // window.location.href = 'favorites.html'; // للتنقل الفعلي
                toggleFavoritesSidebar();
            });

            document.addEventListener('click', (e) => {
                const isClickInsideFab = fabFavorites.contains(e.target);
                const isClickInsideSidebar = favoritesSidebar.contains(e.target);

                // أغلق قائمة المفضلة فقط إذا كان النقر خارجها وخارج زر الـ FAB الخاص بها
                // هذا المنطق لا يأخذ في الاعتبار القوائم الجانبية الأخرى.
                // إذا كنت تدمج هذا في ملف JS واحد، فاستخدم المنطق الأكثر شمولاً الذي قدمته لك سابقاً.
                if (!isClickInsideFab && !isClickInsideSidebar && favoritesSidebar.classList.contains(
                        'open')) {
                    toggleFavoritesSidebar();
                }
            });

            // ** وظيفة تبديل لون زر الإعجاب (القلب) على بطاقات العقارات **
            // هذا الكود يبحث عن جميع الأزرار التي تحتوي على أيقونة قلب
            // ويضيف لها وظيفة تبديل اللون الأحمر عند النقر.
            document.querySelectorAll(".favorite-btn, .listing-card__fav").forEach((button) => {
                button.addEventListener("click", (event) => {
                    // منع الحدث من الانتشار للعناصر الأبوية (مثل بطاقة العقار بأكملها)
                    event.stopPropagation();
                    const icon = event.currentTarget.querySelector("i");
                    // تبديل الكلاسات بين القلب الفارغ (far) والقلب الممتلئ (fas)
                    icon.classList.toggle("far");
                    icon.classList.toggle("fas");
                    // تبديل الكلاس 'active' على الزر نفسه لتطبيق اللون الأحمر في CSS
                    event.currentTarget.classList.toggle("active");
                    console.log("Favorite status toggled for a property.");
                });
            });
        });
    </script>


    @stack('scripts')

</body>

</html>
