<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', cache('settings')['site_name'] ?? 'عقارات غزة') </title>
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
        const userId = "{{ Auth::id() }}";
    </script>

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
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            const simpleChatFab = document.querySelector('.chat-fab-simple');
            const simpleChatSidebar = document.querySelector('.chat-sidebar-simple');
            const simpleChatCloseBtn = document.querySelector('.chat-close-btn-simple');
            const simpleChatListUl = document.querySelector('.chat-list-simple');
            const allChatsSimpleBtn = document.querySelector('.all-chats-btn-simple');
            const favoritesSidebar = document.querySelector('.favorites-sidebar'); // From favorites.js

            if (!csrfToken) {
                console.error('CSRF token not found');
                return;
            }

            // Function to show toast notification (shared with favorites.js)
            function showNotification(message, type = 'success') {
                const toast = document.createElement('div');
                toast.className = `toast-notification toast-${type}`;
                toast.textContent = message;
                document.body.appendChild(toast);

                setTimeout(() => {
                    toast.classList.add('show');
                }, 100);

                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => {
                        toast.remove();
                    }, 300);
                }, 3000);
            }

            // Format time for display
            function formatTime(time) {
                if (!time) return '';
                return new Date(time).toLocaleTimeString('ar-EG', {
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }

            // Get avatar URL
            function getChatAvatar(chat) {
                console.log('chat', chat);
                const user = chat.user_id == userId ? chat.receiver : chat.creator;
                const name = user?.name || 'Unknown';

                return user?.image ??
                    `https://ui-avatars.com/api/?background=0D8ABC&color=fff&name=${encodeURIComponent(name)}`;
            }

            // Get chat name
            function getChatName(chat) {
                const user = chat.user_id == userId ? chat.receiver : chat.creator;
                return user?.name || 'Unknown';
            }

            // Populate chat list
            function populateSimpleChatList() {
                if (!simpleChatListUl) {
                    console.error('Chat list element not found');
                    return;
                }
                simpleChatListUl.innerHTML =
                    '<p style="text-align: center; padding: 20px; color: var(--text-color-light);">جارٍ تحميل الدردشات...</p>';

                fetch('/convers', {
                        headers: {
                            Accept: 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                    })
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then((data) => {
                        console.log('Chats response:', data);
                        simpleChatListUl.innerHTML = '';
                        if (data.length === 0) {
                            simpleChatListUl.innerHTML =
                                '<p style="text-align: center; padding: 20px; color: var(--text-color-light);">لا توجد محادثات لعرضها.</p>';
                        } else {
                            const fragment = document.createDocumentFragment();
                            data.forEach((chat) => {
                                const li = document.createElement('li');
                                li.className = 'chat-item-simple';
                                li.dataset.chatId = chat.id;
                                const lastMessage = chat.last_message?.message ?
                                    chat.last_message.message.startsWith('{') && chat.last_message
                                    .message.endsWith('}') ?
                                    JSON.parse(chat.last_message.message).file_name || 'ملف' :
                                    chat.last_message.message :
                                    'لا رسائل';
                                li.innerHTML = `
                            <img src="${getChatAvatar(chat)}" alt="${getChatName(chat)}" class="chat-avatar-simple">
                            <div class="chat-info-simple">
                                <span class="chat-name-simple">${getChatName(chat)}</span>
                                <p class="last-message-simple">${lastMessage}</p>
                            </div>
                            <span class="chat-time-simple">${formatTime(chat.last_message?.created_at)}</span>
                        `;
                                fragment.appendChild(li);
                            });
                            simpleChatListUl.appendChild(fragment);

                            // Attach click handlers for chat selection
                            document.querySelectorAll('.chat-item-simple').forEach((item) => {
                                item.addEventListener('click', () => {
                                    const chatId = item.dataset.chatId;
                                    console.log('Chatid', chatId);
                                    window.location.href = `/conver?chat_id=${chatId}`;
                                    toggleSimpleChatSidebar();
                                });
                            });
                        }
                    })
                    .catch((error) => {
                        simpleChatListUl.innerHTML =
                            '<p style="text-align: center; padding: 20px; color: var(--text-color-light);">حدث خطأ أثناء تحميل الدردشات.</p>';
                        if (error.message.includes('401')) {
                            showNotification('يرجى تسجيل الدخول لعرض الدردشات', 'error');
                        } else {
                            showNotification('حدث خطأ أثناء تحميل الدردشات.', 'error');
                        }
                    });
            }

            // Toggle chat sidebar
            function toggleSimpleChatSidebar() {
                if (simpleChatSidebar.classList.contains('open')) {
                    simpleChatSidebar.classList.remove('open');
                    simpleChatSidebar.setAttribute('aria-hidden', 'true');
                } else {
                    // Close favorites sidebar if open
                    if (favoritesSidebar && favoritesSidebar.classList.contains('open')) {
                        favoritesSidebar.classList.remove('open');
                        favoritesSidebar.setAttribute('aria-hidden', 'true');
                    }
                    populateSimpleChatList();
                    simpleChatSidebar.classList.add('open');
                    simpleChatSidebar.setAttribute('aria-hidden', 'false');
                }
            }

            // Event listeners
            if (simpleChatFab && simpleChatSidebar && simpleChatCloseBtn) {
                simpleChatFab.addEventListener('click', (event) => {
                    event.stopPropagation();
                    toggleSimpleChatSidebar();
                });

                simpleChatCloseBtn.addEventListener('click', (event) => {
                    event.stopPropagation();
                    toggleSimpleChatSidebar();
                });

                document.addEventListener('click', (event) => {
                    if (!simpleChatSidebar.contains(event.target) && !simpleChatFab.contains(event
                            .target) && simpleChatSidebar.classList.contains('open')) {
                        toggleSimpleChatSidebar();
                    }
                });
            }

            if (allChatsSimpleBtn) {
                allChatsSimpleBtn.addEventListener('click', (event) => {
                    event.preventDefault();
                    window.location.href = allChatsSimpleBtn.getAttribute('href');
                    toggleSimpleChatSidebar();
                });
            }
        });
    </script>


    {{-- <script src="{{ asset('assets/js/favorites.js') }}"></script> --}}
    <script src="{{ asset('assets/js/favorite.js') }}"></script>

    @stack('scripts')

</body>

</html>
