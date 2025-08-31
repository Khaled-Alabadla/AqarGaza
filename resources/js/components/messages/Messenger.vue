<script lang="ts">
import { defineComponent, ref } from 'vue';
import ChatHeader from './ChatHeader.vue';
import ChatContent from './ChatContent.vue';
import ChatFooter from './ChatFooter.vue';
import ChatList from './ChatList.vue';

export default defineComponent({
    components: { ChatHeader, ChatContent, ChatFooter, ChatList },
    props: {
        chat: {
            type: Object,
            default: null,
        },
    },
    setup() {
        const selectedChat = ref(null);
        const isNewMessageModalOpen = ref(false);
        const chats = ref([]);
        const editingMessage = ref(null);

        return {
            selectedChat,
            isNewMessageModalOpen,
            chats,
            editingMessage,
        };
    },
    computed: {
        root() {
            return this.$root;
        },
    },
    methods: {
        handleMessageUpdated({ message, chatId }) {
            const chatIndex = this.chats.findIndex(chat => chat.id === chatId);
            if (chatIndex !== -1) {
                this.chats[chatIndex] = {
                    ...this.chats[chatIndex],
                    last_message: message,
                    updated_at: message.updated_at || new Date().toISOString(),
                };
                this.chats = this.sortChats([...this.chats]);
                this.$root.chats = [...this.chats];
            }
        },
        sortChats(chats) {
            return chats.sort((a, b) => {
                const aTime = a.last_message?.created_at ? new Date(a.last_message.created_at).getTime() : 0;
                const bTime = b.last_message?.created_at ? new Date(b.last_message.created_at).getTime() : 0;
                return bTime - aTime;
            });
        },
        openModal() {
            this.isNewMessageModalOpen = true;
        },
        closeModal() {
            this.isNewMessageModalOpen = false;
            if (this.$refs.newMessageSearch) {
                this.$refs.newMessageSearch.value = '';
            }
            this.filterChats('', this.chats);
        },
        getChatImage(chat) {
            if (!chat) {
                return 'https://ui-avatars.com/api/?background=0D8ABC&color=fff&name=Unknown';
            }
            const user = this.getOtherUser(chat);
            const name = user?.name || 'Unknown';
            const image = user?.image;
            return image ??
                `https://ui-avatars.com/api/?background=0D8ABC&color=fff&name=${encodeURIComponent(name)}`;
        },
        getChatName(chat) {
            if (!chat) {
                return 'Unknown';
            }
            const user = this.getOtherUser(chat);
            const name = user?.name || 'Unknown';
            return name;
        },
        getOtherUser(chat) {
            if (!chat || !this.$root.userId) {
                return null;
            }
            return chat.user_id == this.$root.userId ? chat.receiver : chat.creator;
        },
        filterChats(searchTerm, chats) {
            return chats.map(chat => ({
                ...chat,
                display: this.getChatName(chat).toLowerCase().includes(searchTerm.toLowerCase()) ? 'flex' : 'none',
            }));
        },
        selectChat(chat) {
            this.selectedChat = chat;
            this.closeModal();
            this.$emit('chat-selected', chat);
            this.$root.setChat(chat);
            this.fetchMessages(chat.id);
            this.editingMessage = null;
        },
        fetchMessages(chatId) {
            fetch(`/convers/${chatId}/messages`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.$root.csrf_token,
                },
            })
                .then(response => {
                    if (!response.ok) throw new Error(`Failed to fetch messages: ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    this.$root.messages = data.messages || [];
                })
                .catch(error => {
                });
        },
        sendInitialMessage(chatId, messageText) {
            const receiverId = this.chats.find(chat => chat.id === chatId)?.receiver_id;
            if (!receiverId) return;

            const formData = new FormData();
            formData.append('chat_id', chatId.toString());
            formData.append('sender_id', this.$root.userId.toString());
            formData.append('receiver_id', receiverId.toString());
            formData.append('message', messageText);
            formData.append('_token', this.$root.csrf_token);

            fetch('/messages', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                },
                body: formData,
            })
                .then(response => {
                    if (!response.ok) throw new Error(`Failed to send message: ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    this.$root.messages.push(data.message);
                    this.handleMessageUpdated({ message: data.message, chatId });
                })
                .catch(error => {
                });
        },
        handleEditMessage(message) {
            this.editingMessage = message; // Set the message to be edited
        },
        clearEditing() {
            this.editingMessage = null; // Clear the editing state
        },
    },
    mounted() {
        if (this.$root.chats) {
            this.chats = this.$root.chats.map(chat => ({ ...chat, display: 'flex' }));
        }

        const urlParams = new URLSearchParams(window.location.search);
        const chatId = urlParams.get('chat_id');
        if (chatId) {
            const checkChats = () => {
                if (this.chats.length > 0) {
                    const chat = this.chats.find(c => c.id == chatId);
                    if (chat) {
                        this.selectChat(chat);
                        const propertyTitle = decodeURIComponent(urlParams.get('property_title') || 'العقار');
                        this.sendInitialMessage(chatId, `مرحباً، أنا مهتم بالعقار: ${propertyTitle}`);
                    } else {
                        fetch(`/convers/${chatId}`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': this.$root.csrf_token,
                            },
                        })
                            .then(response => response.json())
                            .then(data => {
                                this.chats.push({ ...data, display: 'flex' });
                                this.$root.chats = [...this.chats];
                                this.selectChat(data);
                                const propertyTitle = decodeURIComponent(urlParams.get('property_title') || 'العقار');
                                this.sendInitialMessage(chatId, `مرحباً، أنا مهتم بالعقار: ${propertyTitle}`);
                            })
                            .catch(error => {
                            });
                    }
                } else {
                    setTimeout(checkChats, 100);
                }
            };
            checkChats();
        }

        this.$watch(
            () => this.$root.chats,
            newChats => {
                this.chats = newChats.map(chat => ({ ...chat, display: 'flex' }));
                if (this.selectedChat && !newChats.some(chat => chat.id === this.selectedChat.id)) {
                    this.selectedChat = null;
                    this.$root.chat = null;
                    this.$root.messages = [];
                }
            }
        );
    }
});
</script>

<template>
    <div class="chat-app">
        <div class="content">
            <ChatList :chats="chats" :selected-chat="selectedChat" @select-chat="selectChat" />
            <main class="chat-window" :style="{ display: selectedChat ? 'flex' : 'none' }">
                <ChatHeader :selected-chat="selectedChat" @back="selectedChat = null" />
                <ChatContent :messages="root.messages" :selected-chat="selectedChat"
                    @edit-message="handleEditMessage" />
                <ChatFooter :chat-id="selectedChat?.id" :editing-message="editingMessage" @clear-editing="clearEditing"
                    @message-updated="handleMessageUpdated" />
            </main>
            <section class="side-chat" :style="{ display: selectedChat ? 'none' : 'flex' }">
                <div class="side-text">
                    <h2>اختر رسالة</h2>
                    <span>اختر من محادثاتك الحالية، أو ابدأ رسالة جديدة، أو يمكنك مواصلة ما بدأته فحسب.</span>
                </div>
                <div class="side-button">
                    <a class="btn-chat" id="new-message-btn" @click="openModal">
                        <i class="fa-solid fa-feather-pointed"></i> رسالة جديدة
                    </a>
                </div>
            </section>
        </div>

        <div class="blur-background" id="blur-background" :class="{ active: isNewMessageModalOpen }"></div>

        <div class="modal" id="new-message-modal" :class="{ active: isNewMessageModalOpen }">
            <div class="modal-header">
                <h2>رسالة جديدة</h2>
                <i class="fas fa-times modal-close" @click="closeModal"></i>
            </div>
            <div class="modal-content">
                <div class="search">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="ابحث عن جهة اتصال" id="new-message-search" ref="newMessageSearch"
                        @input="chats = filterChats($event.target.value, chats)" />
                </div>
                <div class="contacts-list">
                    <div v-if="chats.length === 0">لا توجد محادثات لعرضها</div>
                    <div v-else v-for="otherChat in chats" :key="otherChat.id" class="contact"
                        :data-name="getChatName(otherChat)" :data-img="getChatImage(otherChat)"
                        :style="{ display: otherChat.display }" @click="selectChat(otherChat)">
                        <img :src="getChatImage(otherChat)" alt="Avatar" />
                        <div class="info">
                            <div class="name">{{ getChatName(otherChat) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
