```vue
<template>
    <div class="messages" id="messages" ref="messages">
        <div v-for="message in $root.messages" :key="message.id" class="message"
            :class="{ sent: message.sender_id == userId, received: message.sender_id != userId, deleted: message.message === 'تم حذف الرسالة' }">
            <div class="menu" v-if="message.message !== 'تم حذف الرسالة'">
                <i v-if="message.sender_id == userId" class="fas fa-ellipsis-h"
                    @click="toggleId = message.id; activeMenu = !activeMenu"></i>
                <ul v-if="toggleId == message.id && activeMenu"
                    :style="{ position: 'relative', right: message.sender_id == userId ? '25px' : '', left: message.receiver_id == userId ? '25px' : '' }">
                    <li v-if="message.sender_id == userId"
                        style="display: flex; gap: 5px; align-items: center; cursor: pointer; padding: 2px;"
                        class="delete" @click="deleteMessage(message)">
                        <i class="fas fa-trash" style="font-size: 12px;"></i>حذف
                    </li>
                    <li v-if="message.sender_id == userId && message.type !== 'attachment'"
                        style="display: flex; gap: 5px; align-items: center; cursor: pointer; padding: 2px;"
                        class="edit" @click="startEditMessage(message)">
                        <i class="fas fa-edit" style="font-size: 12px;"></i>تعديل
                    </li>
                </ul>
            </div>
            <div v-if="message.message === 'تم حذف الرسالة'" class="deleted-message message sent">
                تم حذف الرسالة
            </div>
            <div v-else class="bubble"
                :class="{ 'file-bubble': message.type === 'attachment' && !getFileData(message.message)?.mimetype?.startsWith('image/'), 'file-image': getFileData(message.message)?.mimetype?.startsWith('image/') }">
                <!-- Text message -->
                <div v-if="message.type === 'text' || !message.type">
                    {{ message.message }}
                </div>
                <!-- Attachment message -->
                <div v-else-if="message.type === 'attachment'">
                    <div v-if="getFileData(message.message)?.mimetype?.startsWith('image/')" class="image-attachment">
                        <img :src="`${getFileData(message.message)?.file_path}`"
                            style="object-fit:cover; max-width: 100%;" />
                    </div>
                    <div v-else class="file-attachment">
                        <div class="file-icon">
                            <i :class="getFileIcon(getFileData(message.message)?.mimetype)"></i>
                        </div>
                        <div class="file-details">
                            <div class="file-name">{{ getFileData(message.message)?.file_name || 'Unknown File' }}</div>
                            <div class="file-size">{{ formatFileSize(getFileData(message.message)?.file_size || 0) }}
                            </div>
                        </div>
                        <div class="file-actions">
                            <button
                                v-if="getFileData(message.message)?.file_path && getFileData(message.message)?.file_path !== 'uploading...'"
                                @click="downloadFile(getFileData(message.message), message.id)" class="download-btn"
                                :disabled="isDownloading[message.id]">
                                <i class="fas fa-download"></i>
                                <span v-if="isDownloading[message.id]" class="loading-text">جاري التنزيل...</span>
                            </button>
                            <span v-else class="uploading-text">جاري الرفع...</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="info">
                <img class="avatar" :src="getChatImage(message)" alt="Avatar" />
                <div class="time">{{ formatTime(message.created_at) }}</div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, reactive } from 'vue';

interface Message {
    id: number | string;
    message: string;
    sender_id: number;
    chat_id: number | string;
    created_at: string;
    type?: 'text' | 'attachment';
    sender: { name: string; image?: string };
}

interface FileData {
    file_name: string;
    file_size: number;
    mimetype: string;
    file_path: string;
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
    setup() {
        const isDownloading = reactive<Record<string | number, boolean>>({});
        return {
            isDownloading,
        };
    },
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
            fetch(`/convers/${chat}/messages`, {
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
                    this.loading = false;
                });
        },

        getFileData(message: string): FileData | null {
            try {
                return JSON.parse(message);
            } catch (e) {
                return null;
            }
        },

        getFileIcon(mimetype: string = ''): string {
            const type = mimetype.toLowerCase();

            if (type.includes('image/')) return 'fas fa-image';
            if (type.includes('video/')) return 'fas fa-video';
            if (type.includes('audio/')) return 'fas fa-music';
            if (type.includes('pdf')) return 'fas fa-file-pdf';
            if (type.includes('word') || type.includes('document')) return 'fas fa-file-word';
            if (type.includes('excel') || type.includes('spreadsheet')) return 'fas fa-file-excel';
            if (type.includes('powerpoint') || type.includes('presentation')) return 'fas fa-file-powerpoint';
            if (type.includes('zip') || type.includes('rar') || type.includes('tar')) return 'fas fa-file-archive';
            if (type.includes('text/')) return 'fas fa-file-alt';

            return 'fas fa-file';
        },

        formatFileSize(bytes: number): string {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        },

        async downloadFile(fileData: FileData, messageId: string | number) {
            try {
                this.isDownloading[messageId] = true;
                const response = await fetch(`/uploads/messages/${fileData.file_path}`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': (this.$root as any).csrf_token,
                    },
                });

                if (!response.ok) {
                    throw new Error('Failed to download file');
                }

                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = fileData.file_name;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
            } catch (error) {
                alert('فشل في تنزيل الملف');
            } finally {
                this.isDownloading[messageId] = false;
            }
        },

        getChatImage(message: Message) {
            const user = message.sender;
            return user.image ??
                `https://ui-avatars.com/api/?background=0D8ABC&color=fff&name=${encodeURIComponent(user.name)}`;
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
}

.deleted-message {
    font-style: italic;
    color: #6c757d;
    font-size: 0.9em;
    padding: 8px;
    display: flex;
    align-items: center;
    gap: 5px;
}

.message.deleted .bubble {
    display: none;
    /* Hide the bubble for deleted messages */
}

.file-bubble {
    background: none;
    border: 1px solid #dee2e6;
    min-width: 250px;
}

.file-attachment {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px;
}

.file-icon {
    font-size: 24px;
    color: #6c757d;
    min-width: 30px;
    text-align: center;
}

.file-details {
    flex: 1;
    min-width: 0;
}

.file-name {
    font-weight: 500;
    color: #333;
    word-break: break-word;
    margin-bottom: 2px;
}

.file-size {
    color: #6c757d;
    font-size: 0.85em;
}

.file-actions {
    display: flex;
    align-items: center;
}

.download-btn {
    background: #007bff;
    color: white;
    border: none;
    border-radius: 50%;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background-color 0.2s;
}

.download-btn:hover {
    background: #0056b3;
}

.uploading-text {
    color: #6c757d;
    font-size: 0.85em;
    font-style: italic;
}

/* Message bubble styles */
.message.sent .file-bubble {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
}

.message.sent .file-image {
    /* background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); */
    padding: 0;
    color: white;
}

.message.sent .file-name {
    color: white;
}

.message.sent .file-size {
    color: rgba(255, 255, 255, 0.8);
}

.message.sent .file-icon {
    color: rgba(255, 255, 255, 0.9);
}

.message.sent .download-btn {
    background: rgba(255, 255, 255, 0.2);
    color: white;
}

.message.sent .download-btn:hover {
    background: rgba(255, 255, 255, 0.3);
}

.message.sent .uploading-text {
    color: rgba(255, 255, 255, 0.8);
}

/* New styles for image attachments */
.image-attachment {
    max-width: 300px;
    max-height: 300px;
    overflow: hidden;
    border-radius: 10px;
}

.attached-image {
    width: 100%;
    height: auto;
    border-radius: 10px;
    display: block;
}
</style>
