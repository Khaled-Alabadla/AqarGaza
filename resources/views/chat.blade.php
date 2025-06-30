@extends('layouts.front')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/chat_styles.css') }}" />
@endpush

@section('content')
    <div class="container-chat">
        <messenger />
    </div>
@endsection

@push('scripts')
    <script>
        const userId = "{{ auth()->user()->id }}";


        const csrf_token = "{{ csrf_token() }}";
    </script>
    @vite('resources/js/messages.js')
    {{-- <script>
        //  جافاسكربت لتفعيل القائمة الجانبية

        const toggle = document.querySelector('.sidebar-toggle');

        const sidebar_header = document.querySelector('.sidebar-header');

        toggle.addEventListener('click', (e) => {
            e.preventDefault();
            sidebar_header.classList.toggle('open');
            sidebar_header.setAttribute('aria-hidden', sidebar_header.classList.contains('open') ? 'false' :
                'true');

            // بدّل الأيقونة
            const icon = toggle.querySelector('i');
            if (sidebar_header.classList.contains('open')) {
                icon.classList.replace('fa-bars', 'fa-x');
            } else {
                icon.classList.replace('fa-x', 'fa-bars');
            }
        });

        // ====== Contact Search Functionality ======
        const contactSearch = document.querySelector('#contact-search');
        const modalContactSearch = document.querySelector('#modal-contact-search');
        const newMessageSearch = document.querySelector('#new-message-search');
        const contacts = document.querySelectorAll('.sidebar .contacts .contact');
        const modalContacts = document.querySelectorAll('#contacts-modal .contacts-list .contact');
        const newMessageContacts = document.querySelectorAll('#new-message-modal .contacts-list .contact');

        function filterContacts(searchInput, contactList) {
            const searchTerm = searchInput.value.trim().toLowerCase();
            contactList.forEach(contact => {
                const name = contact.dataset.name.toLowerCase();
                contact.style.display = name.includes(searchTerm) ? 'flex' : 'none';
            });
        }

        contactSearch.addEventListener('input', () => filterContacts(contactSearch, contacts));
        modalContactSearch.addEventListener('input', () => filterContacts(modalContactSearch, modalContacts));
        newMessageSearch.addEventListener('input', () => filterContacts(newMessageSearch, newMessageContacts));

        // ====== Contact Selection and Dynamic Avatar Update ======
        const chatWindow = document.querySelector('.chat-window');
        const sidebar = document.querySelector('.sidebar');
        const sideChat = document.querySelector('.side-chat');
        const headerUser = document.querySelector('.chat-window .user');
        const headerImg = headerUser.querySelector('img');
        const headerName = headerUser.querySelector('.name');
        const messages = document.querySelector('#messages');

        function selectContact(contact, modal = null) {
            contacts.forEach(c => c.classList.remove('active'));
            const sidebarContact = Array.from(contacts).find(c => c.dataset.name === contact.dataset.name);
            if (sidebarContact) sidebarContact.classList.add('active');

            const contactName = contact.querySelector('.name').textContent;
            const contactImg = contact.querySelector('img').src;
            headerName.textContent = contactName;
            headerImg.src = contactImg;

            const messageAvatars = messages.querySelectorAll('.message.received .avatar');
            messageAvatars.forEach(avatar => {
                avatar.src = contactImg;
            });

            chatWindow.style.display = 'flex';
            sidebar.style.display = 'none';
            sideChat.style.display = 'none';
            if (modal) closeModal(modal);
            messages.scrollTop = messages.scrollHeight;
        }

        contacts.forEach(contact => {
            contact.addEventListener('click', () => selectContact(contact));
        });

        modalContacts.forEach(contact => {
            contact.addEventListener('click', () => selectContact(contact, contactsModal));
        });

        newMessageContacts.forEach(contact => {
            contact.addEventListener('click', () => selectContact(contact, newMessageModal));
        });

        // ====== Back Arrow Functionality ======
        const backArrow = document.querySelector('.back-arrow');
        backArrow.addEventListener('click', () => {
            chatWindow.style.display = 'none';
            sidebar.style.display = 'flex';
            sideChat.style.display = 'flex';
        });

        // ====== Message Menu Functionality ======
        function attachMessageMenuEvents(message) {
            const menuIcon = message.querySelector('.menu i');
            const deleteBtn = message.querySelector('.delete');
            const editBtn = message.querySelector('.edit');

            menuIcon.addEventListener('click', e => {
                document.querySelectorAll('.menu-options').forEach(list => {
                    if (list !== e.currentTarget.nextElementSibling) list.style.display = 'none';
                });
                const opts = e.currentTarget.nextElementSibling;
                opts.style.display = opts.style.display === 'block' ? 'none' : 'block';
            });

            deleteBtn.addEventListener('click', () => message.remove());
            editBtn.addEventListener('click', () => {
                const bubble = message.querySelector('.bubble');
                const newText = prompt('حرّر الرسالة:', bubble.textContent);
                if (newText !== null) bubble.textContent = newText;
            });
        }

        document.querySelectorAll('.chat-window .message').forEach(attachMessageMenuEvents);

        // ====== Modal Functionality ======
        const blurBackground = document.querySelector('#blur-background');
        const newMessageModal = document.querySelector('#new-message-modal');
        const contactsModal = document.querySelector('#contacts-modal');
        const callsModal = document.querySelector('#calls-modal');
        const notificationsModal = document.querySelector('#notifications-modal');
        const newMessageBtn = document.querySelector('#new-message-btn');
        const contactsBtn = document.querySelector('#contacts-btn');
        const callsBtn = document.querySelector('#calls-btn');
        const notificationsBtn = document.querySelector('#notifications-btn');
        const modalCloses = document.querySelectorAll('.modal-close');

        function openModal(modal) {
            blurBackground.classList.add('active');
            modal.classList.add('active');
            chatWindow.style.display = 'none';
            sidebar.style.display = 'none';
            sideChat.style.display = 'none';
        }

        function closeModal(modal) {
            blurBackground.classList.remove('active');
            modal.classList.remove('active');
            // Only show side chat if chat window is not active
            if (chatWindow.style.display !== 'flex') {
                sidebar.style.display = 'flex';
                sideChat.style.display = 'flex';
            }
            const searchInput = modal.querySelector('input');
            if (searchInput) {
                searchInput.value = '';
                const contactList = modal.querySelectorAll('.contacts-list .contact');
                filterContacts(searchInput, contactList);
            }
        }

        newMessageBtn.addEventListener('click', () => openModal(newMessageModal));
        contactsBtn.addEventListener('click', () => openModal(contactsModal));
        callsBtn.addEventListener('click', () => openModal(callsModal));
        notificationsBtn.addEventListener('click', () => openModal(notificationsModal));

        modalCloses.forEach(closeBtn =>
            closeBtn.addEventListener('click', () => {
                closeModal(closeBtn.closest('.modal'));
            })
        );

        // ====== Call Icon Functionality ======
        document.querySelectorAll('.call-icon').forEach(icon => {
            icon.addEventListener('click', () => {
                const name = icon.closest('.call').querySelector('.name').textContent;
                alert(`جاري الاتصال بـ ${name}`);
            });
        });

        // ====== Save Notifications Settings ======
        const saveButton = document.querySelector('.save-button');
        saveButton.addEventListener('click', () => {
            const settings = document.querySelectorAll('.notifications-settings input[type="checkbox"]');
            const settingsStatus = Array.from(settings).map(s => ({
                name: s.parentElement.querySelector('label').textContent,
                enabled: s.checked
            }));
            alert('تم حفظ إعدادات الإشعارات:\n' + JSON.stringify(settingsStatus, null, 2));
        });

        // ====== Send Message Functionality ======
        const messageInput = document.querySelector('#message-input');
        const sendMessageBtn = document.querySelector('#send-message');

        function sendMessage() {
            const text = messageInput.value.trim();
            if (text === '') return;

            const message = document.createElement('div');
            message.classList.add('message', 'sent');
            message.innerHTML = `
	<div class="menu">
		<i class="fas fa-ellipsis-h"></i>
		<ul style="right: 25px;" class="menu-options">
			<li class="delete"><i class="fas fa-trash"></i>حذف</li>
			<li class="edit"><i class="fas fa-edit"></i>تعديل</li>
		</ul>
	</div>
	<div class="bubble">${text}</div>
	<div class="info">
		<img class="avatar" src="../images/khaled.jpg" alt="My Avatar">
		<div class="time">${new Date().toLocaleTimeString('ar-EG', { hour: '2-digit', minute: '2-digit' })}</div>
	</div>
	`;

            messages.appendChild(message);
            messageInput.value = '';
            messages.scrollTop = messages.scrollHeight;
            attachMessageMenuEvents(message);
        }

        sendMessageBtn.addEventListener('click', sendMessage);
        messageInput.addEventListener('keypress', e => {
            if (e.key === 'Enter') sendMessage();
        });
    </script> --}}
@endpush
