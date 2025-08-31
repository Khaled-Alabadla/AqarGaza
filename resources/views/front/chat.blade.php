@extends('layouts.front')

<script>
    // const userId = "{{ auth()->user()->id }}";

    const csrf_token = "{{ csrf_token() }}";
</script>

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/conver_styles.css') }}" />
@endpush

@section('content')
    <div class="container-chat">
        <messenger />
    </div>
@endsection

@push('scripts')
    {{-- @vite('resources/js/messages.js') --}}
    <script src="{{ asset('build/assets/messages-7JXZjb8N.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const chatWindow = document.querySelector('.chat-window');
            const sidebar = document.querySelector('.sidebar');
            const sideChat = document.querySelector('.side-chat');

            setTimeout(() => {
                let contacts = document.querySelectorAll('.sidebar .contacts .contact');
                const modalContacts = document.querySelectorAll(
                    '.modal .modal-content .contacts-list .contact');
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
    </script>
@endpush
