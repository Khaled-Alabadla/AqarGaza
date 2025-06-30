<template>
    <div class="footer">
        <form @submit.prevent="sendMessage" style="width: 100%; display: flex;">
            <input type="hidden" name="_token" :value="csrfToken" />
            <div class="input-group">
                <i class="far fa-smile smile"></i>
                <input type="text" placeholder="اكتب رسالة..." id="message-input" v-model.trim="messageText"
                    ref="messageInput" @keypress.enter.prevent="sendMessage" />
                <i class="fas fa-paper-plane send" id="send-message" @click.prevent="sendMessage"></i>
            </div>
            <div class="footer-icons">
                <i class="fas fa-microphone"></i>
                <i class="fas fa-paperclip"></i>
                <i class="fas fa-ellipsis-h"></i>
            </div>
        </form>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref, nextTick, watch } from 'vue';

// Generate a unique temporary ID
const generateTempId = () => `temp_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`;

interface Message {
    id: number | string;
    message: string;
    sender_id: number;
    chat_id: number | string;
    receiver_id: number;
    created_at: string;
    sender: { name: string; image?: string };
}

export default defineComponent({
    name: 'ChatFooter',
    props: {
        chatId: {
            type: [Number, String],
            required: true,
        },
        editingMessage: {
            type: Object as () => Message | null,
            default: null,
        },
    },
    emits: ['clear-editing', 'message-updated'],
    setup(props) {
        const messageText = ref<string>('');
        const messageInput = ref<HTMLInputElement | null>(null);

        watch(() => props.editingMessage, (newMessage) => {
            if (newMessage) {
                messageText.value = newMessage.message;
                nextTick(() => {
                    messageInput.value?.focus();
                });
            } else {
                messageText.value = '';
            }
        });

        return {
            messageText,
            messageInput,
        };
    },
    data() {
        return {
            csrfToken: (this.$root as any).csrf_token as string,
            userId: (this.$root as any).userId as number,
            userName: (this.$root as any).userName || 'User', // Fallback name
            userImage: (this.$root as any).userImage as string | undefined, // User image
            isSending: false,
        };
    },
    methods: {
        async sendMessage() {
            if (!this.messageText || this.isSending || !this.chatId) return;

            this.isSending = true;
            const receiverId =
                (this.$root as any).selectedChat.user_id == this.userId
                    ? (this.$root as any).selectedChat.receiver_id
                    : (this.$root as any).selectedChat.user_id;

            // Derive sender data from selectedChat if userName/userImage are missing
            const sender =
                this.userName && this.userImage !== undefined
                    ? { name: this.userName, image: this.userImage }
                    : {
                        name: (this.$root as any).selectedChat.creator.id == this.userId
                            ? (this.$root as any).selectedChat.creator.name || 'User'
                            : (this.$root as any).selectedChat.receiver.name || 'User',
                        image: (this.$root as any).selectedChat.creator.id == this.userId
                            ? (this.$root as any).selectedChat.creator.image
                            : (this.$root as any).selectedChat.receiver.image,
                    };

            if (this.editingMessage) {
                // Update existing message
                const messageToUpdate = this.editingMessage;
                try {
                    const response = await fetch(`/messages/${messageToUpdate.id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken,
                        },
                        body: JSON.stringify({
                            message: this.messageText,
                            chat_id: this.chatId,
                            sender_id: this.userId,
                            receiver_id: receiverId,
                        }),
                    });

                    if (!response.ok) {
                        throw new Error(`Failed to update message: ${response.status}`);
                    }

                    const data = await response.json();
                    const index = (this.$root as any).messages.findIndex((m: Message) => m.id == messageToUpdate.id);
                    if (index !== -1) {
                        (this.$root as any).messages[index] = {
                            ...data.message,
                            sender,
                        };
                    }

                    this.$emit('message-updated', {
                        message: { ...data.message, sender },
                        chatId: this.chatId,
                        isTemp: false,
                    });

                    this.$emit('clear-editing');
                } catch (error) {
                    console.error('Error updating message:', error);
                    this.messageText = messageToUpdate.message;
                } finally {
                    this.isSending = false;
                    await nextTick();
                    this.messageInput?.focus();
                }
            } else {
                // Send new message
                const tempMessage: Message = {
                    id: generateTempId(),
                    message: this.messageText,
                    sender_id: this.userId,
                    chat_id: this.chatId,
                    receiver_id: receiverId,
                    created_at: new Date().toISOString(),
                    sender,
                };

                // Add temporary message immediately
                (this.$root as any).messages.push(tempMessage);
                const messageText = this.messageText;
                this.messageText = '';

                // Emit optimistic update
                this.$emit('message-updated', {
                    message: tempMessage,
                    chatId: this.chatId,
                    isTemp: true,
                });

                try {
                    const response = await fetch('/messages', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken,
                        },
                        body: JSON.stringify({
                            message: messageText,
                            chat_id: this.chatId,
                            sender_id: this.userId,
                            receiver_id: receiverId,
                        }),
                    });

                    if (!response.ok) {
                        throw new Error(`Failed to send message: ${response.status}`);
                    }

                    const data = await response.json();
                    const index = (this.$root as any).messages.findIndex((m: Message) => m.id == tempMessage.id);
                    if (index !== -1) {
                        (this.$root as any).messages[index] = {
                            ...data.message,
                            sender,
                        };
                    }

                    // Emit server-confirmed message
                    this.$emit('message-updated', {
                        message: { ...data.message, sender },
                        chatId: this.chatId,
                        isTemp: false,
                    });
                } catch (error) {
                    console.error('Error sending message:', error);
                    const index = (this.$root as any).messages.findIndex((m: Message) => m.id == tempMessage.id);
                    if (index !== -1) {
                        (this.$root as any).messages.splice(index, 1);
                    }
                    this.messageText = messageText;

                    // Notify ChatList to remove temporary message
                    this.$emit('message-updated', {
                        message: tempMessage,
                        chatId: this.chatId,
                        isTemp: true,
                        failed: true,
                    });
                } finally {
                    this.isSending = false;
                    await nextTick();
                    this.messageInput?.focus();
                }
            }
        },
    },
});
</script>
