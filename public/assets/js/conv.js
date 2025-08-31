document.addEventListener('DOMContentLoaded', () => {
    const chatWindow = document.querySelector('.chat-window');
    const sidebar = document.querySelector('.sidebar');
    const sideChat = document.querySelector('.side-chat');

    setTimeout(() => {
        let contacts = document.querySelectorAll('.sidebar .contacts .contact');
        const modalContacts = document.querySelectorAll('.modal .modal-content .contacts-list .contact');
        const backArrow = document.querySelector('.back-arrow');

        contacts.forEach((el) => {
            el.onclick = () => {
                chatWindow.style.display = 'flex';
                sidebar.style.display = 'none';
                sideChat.style.display = 'none';
            };
        });

        modalContacts.forEach((el) => {
            el.onclick = () => {
                console.log(el);
                chatWindow.style.display = 'flex';
                sidebar.style.display = 'none';
                sideChat.style.display = 'none';
            };
        });

        backArrow.addEventListener('click', () => {
            chatWindow.style.display = 'none';
            sidebar.style.display = 'flex';
            sideChat.style.display = 'flex';
        });
    }, 2000);
});
