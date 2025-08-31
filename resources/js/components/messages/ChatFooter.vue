<template>
    <div class="footer">
        <form @submit.prevent="sendMessage" style="width: 100%; display: flex; justify-content: space-between;">
            <input type="hidden" name="_token" :value="$root.csrfToken" />
            <div class="input-group">
                <i class="far fa-smile smile"></i>
                <input type="text" placeholder="اكتب رسالة..." id="message-input" v-model.trim="messageText"
                    ref="messageInput" @keypress.enter.prevent="sendMessage" />
                <input type="file" ref="fileInput" @change="handleFileSelect" style="display: none;" />
                <i class="fas fa-paper-plane send" id="send-message" @click.prevent="sendMessage"></i>
            </div>
            <div class="footer-icons">
                <!-- <i class="fas fa-microphone"></i> -->
                <i class="fas fa-paperclip" @click="triggerFileInput" style="cursor: pointer;"></i>
                <!-- <i class="fas fa-ellipsis-h"></i> -->
            </div>
        </form>

        <!-- File preview when file is selected -->
        <div v-if="selectedFile" class="file-preview">
            <div class="file-info">
                <i class="fas fa-file-alt"></i>
                <span class="file-name">{{ selectedFile.name }}</span>
                <span class="file-size">({{ formatFileSize(selectedFile.size) }})</span>
                <i class="fas fa-times" @click="clearFile" style="cursor: pointer; color: red;"></i>
            </div>
        </div>
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
    type: 'text' | 'attachment';
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
        const fileInput = ref<HTMLInputElement | null>(null);
        const selectedFile = ref<File | null>(null);

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
            fileInput,
            selectedFile,
        };
    },
    data() {
        return {
            csrfToken: (this.$root as any).csrfToken as string,
            userId: (this.$root as any).userId as number,
            userName: (this.$root as any).userName || 'User',
            userImage: (this.$root as any).userImage as string | undefined,
            isSending: false,
        };
    },
    methods: {
        triggerFileInput() {
            this.fileInput?.click();
        },

        handleFileSelect(event: Event) {
            const target = event.target as HTMLInputElement;
            if (target.files && target.files.length > 0) {
                this.selectedFile = target.files[0];
                // Automatically send the message when a file is selected
                this.sendMessage();
            }
        },

        clearFile() {
            this.selectedFile = null;
            if (this.fileInput) {
                this.fileInput.value = '';
            }
        },

        formatFileSize(bytes: number): string {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        },

        async sendMessage() {
            if ((!this.messageText && !this.selectedFile) || this.isSending || !this.chatId) return;

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
                // Update existing message (text only, no file editing)
                const messageToUpdate = this.editingMessage;
                try {
                    const response = await fetch(`/messages/${messageToUpdate.id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': this.$root.csrfToken,
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
                    this.messageText = messageToUpdate.message;
                } finally {
                    this.isSending = false;
                    await nextTick();
                    this.messageInput?.focus();
                }
            } else {
                // Send new message
                const messageType = this.selectedFile ? 'attachment' : 'text';
                const tempMessage: Message = {
                    id: generateTempId(),
                    message: this.selectedFile ? JSON.stringify({
                        file_name: this.selectedFile.name,
                        file_size: this.selectedFile.size,
                        mimetype: this.selectedFile.type,
                        file_path: 'uploading...'
                    }) : this.messageText,
                    sender_id: this.userId,
                    chat_id: this.chatId,
                    receiver_id: receiverId,
                    created_at: new Date().toISOString(),
                    type: messageType,
                    sender,
                };

                // Add temporary message immediately
                (this.$root as any).messages.push(tempMessage);
                const messageText = this.messageText;
                const fileToSend = this.selectedFile;

                // Clear inputs
                this.messageText = '';
                this.clearFile();

                // Emit optimistic update
                this.$emit('message-updated', {
                    message: tempMessage,
                    chatId: this.chatId,
                    isTemp: true,
                });

                try {
                    const formData = new FormData();
                    formData.append('chat_id', this.chatId.toString());
                    formData.append('sender_id', this.userId.toString());
                    formData.append('receiver_id', receiverId.toString());
                    formData.append('_token', this.$root.csrfToken);
                    console.log(fileToSend);

                    if (fileToSend) {
                        formData.append('attachment', fileToSend);
                    }

                    if (messageText) {
                        formData.append('message', messageText);
                    }

                    const response = await fetch('/messages', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': this.$root.csrfToken,
                        },
                        body: formData,
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
                    const index = (this.$root as any).messages.findIndex((m: Message) => m.id == tempMessage.id);
                    if (index !== -1) {
                        (this.$root as any).messages.splice(index, 1);
                    }
                    this.messageText = messageText;
                    this.selectedFile = fileToSend;

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

<style scoped>
.file-preview {
    background: #f5f5f5;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 10px;
    margin-top: 10px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.file-info {
    display: flex;
    align-items: center;
    gap: 8px;
    flex: 1;
}

.file-name {
    font-weight: 500;
    color: #333;
}

.file-size {
    color: #666;
    font-size: 0.9em;
}

.footer-icons i:hover {
    color: #007bff;
}
</style>
