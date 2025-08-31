// messages.js
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import { createApp } from 'vue/dist/vue.esm-bundler';
import Messenger from './components/messages/Messenger.vue';

window.Pusher = Pusher;

const chatApp = createApp({
    data() {
        return {
            chats: [],
            chat: null,
            messages: [],
            userId: userId,
            csrfToken: csrf_token,
            laravelEcho: null,
            users: [],
            chatChannel: null,
            selectedChat: null,
        };
    },
    mounted() {
        if (!this.userId) {
            return;
        }
        fetch('/convers', {
            headers: {
                Accept: 'application/json',
                'X-CSRF-TOKEN': this.csrfToken,
            },
        })
            .then((response) => response.json())
            .then((data) => {
                this.chats = data || [];
            })
            .catch((error) => {});
        this.laravelEcho = new Echo({
            broadcaster: 'pusher',
            key: '3f245c318aec1d4ea6e1',
            cluster: 'ap2',
            forceTLS: true,
        });

        this.laravelEcho
            .join(`Messenger.${this.userId}`)
            .listen('.new-message', (data) => {
                let exists = false;
                for (let i = 0; i < this.chats.length; i++) {
                    let chat = this.chats[i];
                    if (chat.id === data.message.chat_id) {
                        if (!chat.hasOwnProperty('new_messages')) {
                            chat.new_messages = 0;
                        }
                        chat.new_messages++;
                        chat.last_message = data.message;
                        exists = true;
                        this.chats.splice(i, 1);
                        this.chats.unshift(chat);
                        if (this.chat && this.chat.id === chat.id) {
                            this.messages.push(data.message);
                        }
                        break;
                    }
                }
                if (!exists) {
                    fetch(`/convers/${data.message.chat_id}`, {
                        headers: {
                            Accept: 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken,
                        },
                    })
                        .then((response) => response.json())
                        .then((chat) => {
                            this.chats.unshift(chat);
                        })
                        .catch((error) => {});
                }
            })
            .listen('.message.updated', (data) => {
                for (let i = 0; i < this.chats.length; i++) {
                    let chat = this.chats[i];
                    if (chat.id === data.message.chat_id) {
                        chat.last_message = data.message;
                        this.chats.splice(i, 1);
                        this.chats.unshift(chat);
                        if (this.chat && this.chat.id === chat.id) {
                            const messageIndex = this.messages.findIndex((m) => m.id === data.message.id);
                            if (messageIndex !== -1) {
                                this.messages[messageIndex] = data.message;
                            }
                        }
                        break;
                    }
                }
            })
            .listen('.message.deleted', (data) => {
                const messageIndex = this.messages.findIndex((m) => m.id === data.message.id);
                if (messageIndex !== -1) {
                    this.messages[messageIndex] = data.message;
                }
                for (let i = 0; i < this.chats.length; i++) {
                    let chat = this.chats[i];
                    if (chat.id === data.message.chat_id) {
                        chat.last_message = data.message;
                        this.chats.splice(i, 1);
                        this.chats.unshift(chat);
                        break;
                    }
                }
                this.chats = [...this.chats]; // Trigger reactivity
            })
            .listen('.chat.deleted', (data) => {
                const chatIndex = this.chats.findIndex((chat) => chat.id === data.chat_id);
                if (chatIndex !== -1) {
                    this.chats.splice(chatIndex, 1);
                }
                if (this.chat && this.chat.id === data.chat_id) {
                    this.chat = null;
                    this.selectedChat = null;
                    this.messages = [];
                }
                this.chats = [...this.chats]; // Trigger reactivity
            });

        this.chatChannel = this.laravelEcho
            .join('Chat')
            .joining((user) => {
                for (let chat of this.chats) {
                    let otherUser = this.getOtherUser(chat);
                    if (otherUser && otherUser.id === user.id) {
                        otherUser.isOnline = true;
                        break;
                    }
                }
            })
            .leaving((user) => {
                for (let chat of this.chats) {
                    let otherUser = this.getOtherUser(chat);
                    if (otherUser && otherUser.id === user.id) {
                        otherUser.isOnline = false;
                        break;
                    }
                }
            })
            .listenForWhisper('typing', (e) => {
                let user = this.findUser(e.id, e.chat_id);
                if (user) user.isTyping = true;
            })
            .listenForWhisper('stopped-typing', (e) => {
                let user = this.findUser(e.id, e.chat_id);
                if (user) user.isTyping = false;
            });
    },
    methods: {
        getOtherUser(chat) {
            if (!chat) return null;
            return chat.creator_id === this.userId ? chat.receiver : chat.creator;
        },
        findUser(id, chat_id) {
            for (let chat of this.chats) {
                if (chat.id === chat_id) {
                    let otherUser = this.getOtherUser(chat);
                    if (otherUser && otherUser.id === id) {
                        return otherUser;
                    }
                }
            }
            return null;
        },
        markAsRead(chat = null) {
            if (!chat) chat = this.chat;
            if (!chat) return;
            fetch(`/convers/${chat.id}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                },
                body: JSON.stringify({
                    _method: 'PUT',
                }),
            })
                .then((response) => response.json())
                .then(() => {
                    chat.new_messages = 0;
                })
                .catch((error) => {});
        },
        deleteMessage(message, target) {
            fetch(`/messages/${message.id}`, {
                method: 'POST', // 👈 نخليها POST
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                },
                body: JSON.stringify({
                    _method: 'DELETE', // 👈 Laravel يفهم إنه DELETE
                    target,
                }),
            })
                .then((response) => response.json())
                .then((data) => {
                    const messageIndex = this.messages.findIndex((m) => m.id === message.id);
                    if (messageIndex !== -1) {
                        this.messages[messageIndex].message = 'تم حذف الرسالة';
                    }
                    const chatIndex = this.chats.findIndex((chat) => chat.id === message.chat_id);
                    if (chatIndex !== -1) {
                        this.chats[chatIndex].last_message = {
                            ...this.chats[chatIndex].last_message,
                            message: 'تم حذف الرسالة',
                        };
                    }
                    if (data.is_chat_deleted) {
                        const chatIndex = this.chats.findIndex((chat) => chat.id === data.chat_id);
                        if (chatIndex !== -1) {
                            this.chats.splice(chatIndex, 1);
                        }
                        if (this.chat && this.chat.id === data.chat_id) {
                            this.chat = null;
                            this.selectedChat = null;
                            this.messages = [];
                        }
                    }
                    this.chats = [...this.chats]; // Trigger reactivity
                })
                .catch((error) => {});
        },
        setChat(chat) {
            this.selectedChat = chat;
            this.chat = chat;
        },
    },
});

chatApp.component('messenger', Messenger);
chatApp.mount('.container-chat');
