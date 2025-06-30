<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'عقارات غزة')</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/all.min.css') }}" />
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

    @stack('scripts')

</body>

</html>
