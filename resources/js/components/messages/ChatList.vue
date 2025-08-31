<template>
    <div class="sidebar" :class="{ open: isSidebarOpen }" aria-hidden="!isSidebarOpen">
        <div class="sidebar_header">
            <a href="#" class="sidebar-toggle" @click.prevent="toggleSidebar">
                <i :class="isSidebarOpen ? 'fas fa-x' : 'fas fa-bars'"></i>
            </a>
        </div>
        <div class="search">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="الأشخاص والمجموعات والدردشات" id="contact-search" v-model="searchTerm" />
        </div>
        <div class="contacts">
            <div v-if="loading">جاري تحميل المحادثات...</div>
            <div v-else-if="error">{{ error }}</div>
            <div v-else-if="filteredChats.length == 0">لا توجد محادثات مطابقة</div>
            <div v-else v-for="chat in filteredChats" :key="chat.id" class="contact"
                :class="{ active: chat.id == selectedChat?.id }" :data-name="getOtherUser(chat)?.name || 'Unknown'"
                :data-img="getOtherUser(chat)?.image" @click="selectChat(chat)">
                <img :src="getOtherUser(chat).image ??
                    `https://ui-avatars.com/api/?background=0D8ABC&color=fff&name=${encodeURIComponent(
                        getOtherUser(chat)?.name || 'Unknown'
                    )}`" alt="Avatar" />
                <div class="info">
                    <div class="name">{{ getOtherUser(chat)?.name || 'Unknown' }}</div>
                    <div class="time">{{ formatTime(chat.last_message?.created_at) }}</div>
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div :class="{ 'text-bold': chat.unread_count > 0 }" class="preview">
                            {{
                                isJson(chat.last_message?.message)
                                    ? JSON.parse(chat.last_message.message).file_name
                                    : chat.last_message?.message || 'لا رسائل'
                            }}
                        </div>
                        <div style="font-weight:bold; font-size: 12px"
                            v-if="(chat.unread_count || chat.new_messages || 0) > 0" class="unread-count">
                            {{ chat.unread_count || chat.new_messages || 0 }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref, watch } from 'vue';
import { debounce } from 'lodash';

interface User {
    id: number;
    name: string;
    image?: string;
}

interface Message {
    id: number | string;
    message: string;
    created_at: string;
    sender: { name: string; image?: string };
}

interface Chat {
    id: number;
    user_id: number;
    receiver_id: number;
    creator: User;
    receiver: User;
    last_message?: Message;
}

export default defineComponent({
    name: 'ChatList',
    props: {
        chats: {
            type: Array as () => Chat[],
            default: () => [],
        },
        selectedChat: {
            type: Object as () => Chat | null,
            default: null,
        },
    },
    setup() {
        const localChats = ref<Chat[]>([]);
        const filteredChats = ref<Chat[]>([]);
        const loading = ref<boolean>(true);
        const error = ref<string | null>(null);
        const isSidebarOpen = ref<boolean>(false);
        const searchTerm = ref<string>('');

        return {
            localChats,
            filteredChats,
            loading,
            error,
            isSidebarOpen,
            searchTerm,
        };
    },
    data() {
        return {
            pollingInterval: null as number | null,
        };
    },
    methods: {
        isJson(str: string): boolean {
            if (!str || typeof str !== 'string') return false;
            try {
                const parsed = JSON.parse(str);
                return parsed && typeof parsed === 'object';
            } catch (e) {
                return false;
            }
        },
        toggleSidebar() {
            this.isSidebarOpen = !this.isSidebarOpen;
        },
        filterChats(searchTerm: string) {
            if (!searchTerm.trim()) {
                this.filteredChats = [...this.localChats];
                return;
            }
            const lowerSearch = searchTerm.toLowerCase();
            this.filteredChats = this.localChats.filter(chat =>
                this.getOtherUser(chat)?.name?.toLowerCase().includes(lowerSearch)
            );
        },
        selectChat(chat: Chat) {
            this.$emit('select-chat', chat);
            (this.$root as any).setChat(chat);
            (this.$root as any).markAsRead(chat);
        },
        getOtherUser(chat: Chat | null): User | null {
            if (!chat) return null;
            return chat.user_id == (this.$root as any).userId ? chat.receiver : chat.creator;
        },
        formatTime(time?: string): string {
            return time ? new Date(time).toLocaleTimeString('ar-EG', { hour: '2-digit', minute: '2-digit' }) : '';
        },
        sortChats(chats: Chat[]): Chat[] {
            return chats.sort((a, b) => {
                const aTime = a.last_message?.created_at ? new Date(a.last_message.created_at).getTime() : 0;
                const bTime = b.last_message?.created_at ? new Date(b.last_message.created_at).getTime() : 0;
                return bTime - aTime;
            });
        },
        async fetchChats() {
            if (!(this.$root as any).userId) {
                this.error = 'يرجى تسجيل الدخول لعرض المحادثات';
                this.loading = false;
                return;
            }
            this.loading = true;
            try {
                const response = await fetch('/convers', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': (this.$root as any).csrf_token,
                    },
                });
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data: Chat[] = await response.json();
                this.localChats = this.sortChats(data || []);
                this.filterChats(this.searchTerm);
                (this.$root as any).chats = this.localChats;
            } catch (error: any) {
                this.error = error.message.includes('401')
                    ? 'يرجى تسجيل الدخول لعرض المحادثات'
                    : 'فشل في تحميل المحادثات. حاول مرة أخرى.';
            } finally {
                this.loading = false;
            }
        },
        updateChatMessage(message: Message, chatId: number, isTemp = false, failed = false) {
            const chatIndex = this.localChats.findIndex(chat => chat.id == chatId);
            if (chatIndex === -1) return;

            if (failed) {
                if (this.localChats[chatIndex].last_message?.id === message.id) {
                    this.localChats[chatIndex].last_message = undefined;
                }
            } else {
                const existingMessageId = this.localChats[chatIndex].last_message?.id;
                if (isTemp || !existingMessageId || message.id === existingMessageId) {
                    this.localChats[chatIndex].last_message = { ...message };
                } else if (new Date(message.created_at).getTime() > new Date(this.localChats[chatIndex].last_message?.created_at || 0).getTime()) {
                    this.localChats[chatIndex].last_message = { ...message };
                }
            }

            this.localChats = this.sortChats([...this.localChats]);
            this.filterChats(this.searchTerm);
            (this.$root as any).chats = [...this.localChats];
        },
        setupWebSocket() {
            if (!(this.$root as any).userId) return;
            (window as any).Echo?.channel(`Messenger.${(this.$root as any).userId}`)
                .listen('.new-message', (e: { message: Message; chat_id: number }) => {
                    const chatIndex = this.localChats.findIndex(chat => chat.id == e.chat_id);
                    if (chatIndex !== -1) {
                        const currentLastMessage = this.localChats[chatIndex].last_message;
                        if (
                            !currentLastMessage ||
                            typeof currentLastMessage.id === 'string' ||
                            new Date(e.message.created_at).getTime() > new Date(currentLastMessage.created_at).getTime()
                        ) {
                            this.updateChatMessage(e.message, e.chat_id);
                        }
                    }
                })
                .listen('.message.deleted', (e: { message: Message; chat_id: number }) => {
                    this.updateChatMessage(e.message, e.chat_id);
                });
        },
        startPolling() {
            if (this.pollingInterval) return;
            this.pollingInterval = window.setInterval(async () => {
                try {
                    const response = await fetch('/convers', {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': (this.$root as any).csrf_token,
                        },
                    });
                    if (response.ok) {
                        const data: Chat[] = await response.json();
                        this.localChats = this.sortChats(data || []).map(chat => {
                            const existingChat = this.localChats.find(c => c.id === chat.id);
                            if (existingChat?.last_message?.id && typeof existingChat.last_message.id === 'string') {
                                return { ...chat, last_message: existingChat.last_message };
                            }
                            return chat;
                        });
                        this.filterChats(this.searchTerm);
                        (this.$root as any).chats = [...this.localChats];
                    }
                } catch (error) {
                }
            }, 10000);
        },
    },
    watch: {
        chats(newChats: Chat[]) {
            this.localChats = this.sortChats([...newChats]);
            this.filterChats(this.searchTerm);
            this.loading = false;
        },
        searchTerm: {
            handler: debounce(function (this: any, newVal: string) {
                this.filterChats(newVal);
            }, 300),
        },
    },
    mounted() {

        this.fetchChats();
        if (this.chats.length > 0) {
            this.localChats = this.sortChats([...this.chats]);
            this.filterChats(this.searchTerm);
            this.loading = false;
        }
        if ((window as any).Echo) {
            this.setupWebSocket();
        } else {
            this.startPolling();
        }
        // this.$root.$on('message-updated', ({ message, chatId, isTemp, failed }: { message: Message; chatId: number; isTemp?: boolean; failed?: boolean }) => {
        //     this.updateChatMessage(message, chatId, isTemp, failed);
        // });
    },
    beforeUnmount() {
        if (this.pollingInterval) {
            clearInterval(this.pollingInterval);
        }
        if ((window as any).Echo) {
            (window as any).Echo.leave(`Messenger.${(this.$root as any).userId}`);
        }
        this.$root.$off('message-updated');
    },
});
</script>
<style scoped>
.text-bold {
    font-weight: bold;
}
</style>
