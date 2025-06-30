<template>
    <div class="messages" id="messages" ref="messages">
        <div v-for="message in $root.messages" :key="message.id" class="message"
            :class="{ sent: message.sender_id == userId, received: message.sender_id != userId }">
            <div class="menu" v-if="message.message !== 'تم حذف الرسالة'">
                <i class="fas fa-ellipsis-h" @click="toggleId = message.id; activeMenu = !activeMenu"></i>
                <ul v-if="toggleId == message.id && activeMenu"
                    :class="{ 'menu-options': true, active: activeMenu == message.id }"
                    :style="{ right: message.sender_id == userId ? '25px' : '' }">
                    <li class="delete" @click="deleteMessage(message)"><i class="fas fa-trash"></i>حذف</li>
                    <li class="edit" @click="startEditMessage(message)"><i class="fas fa-edit"></i>تعديل</li>
                </ul>
            </div>
            <div class="bubble">{{ message.message }}</div>
            <div class="info">
                <img class="avatar" :src="getChatImage(message)" alt="Avatar"
                    @error="handleImageError($event, message)" />
                <div class="time">{{ formatTime(message.created_at) }}</div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';

interface Message {
    id: number | string;
    message: string;
    sender_id: number;
    chat_id: number | string;
    created_at: string;
    sender: { name: string; image?: string };
}

export default defineComponent({
    name: 'ChatContent',
    props: {
        messages: {
            type: Array as () => Message[],
            required: true,
        },
        selectedChat: {
            type: Object,
            default: null,
        },
    },
    emits: ['edit-message'],
    data() {
        return {
            userId: (this.$root as any).userId as number,
            activeMenu: false,
            toggleId: 0,
            loading: false,
        };
    },
    watch: {
        '$root.messages': {
            handler() {
                this.$nextTick(() => {
                    const messagesEl = this.$refs.messages as HTMLElement;
                    messagesEl.scrollTop = messagesEl.scrollHeight;
                });
            },
            deep: true,
        },
    },
    methods: {
        fetchMessages(chat: any) {
            if (!chat) return;
            this.loading = true;
            fetch(`/chats/${chat}/messages`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': (this.$root as any).csrf_token,
                },
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    (this.$root as any).messages = data.messages.map((msg: any) => ({
                        ...msg,
                        sender: {
                            name: msg.sender?.name || (this.$root as any).selectedChat?.creator?.name || 'User',
                            image: msg.sender?.image || (this.$root as any).selectedChat?.creator?.image,
                        },
                    }));
                    this.loading = false;
                })
                .catch(error => {
                    console.error('Error fetching messages:', error);
                    this.loading = false;
                });
        },
        getChatImage(message: Message) {
            const user = message.sender;
            return user.image
                ? `/uploads/${user.image}`
                : `https://ui-avatars.com/api/?background=0D8ABC&color=fff&name=${encodeURIComponent(user.name)}`;
        },
        handleImageError(event: Event, message: Message) {
            const img = event.target as HTMLImageElement;
            img.src = `https://ui-avatars.com/api/?background=0D8ABC&color=fff&name=${encodeURIComponent(message.sender.name)}`;
        },
        formatTime(time: string) {
            return new Date(time).toLocaleTimeString('ar-EG', { hour: '2-digit', minute: '2-digit' });
        },
        deleteMessage(message: Message) {
            (this.$root as any).deleteMessage(message, 'self');
            this.activeMenu = false;
            this.toggleId = 0;
        },
        startEditMessage(message: Message) {
            this.$emit('edit-message', message);
            this.activeMenu = false;
            this.toggleId = 0;
        },
    },
    mounted() {
        this.fetchMessages((this.$root as any).chat);
    },
});
</script>

<style scoped>
.avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
    display: block;
    /* Ensure immediate rendering */
}
</style>
