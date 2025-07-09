<template>
    <div class="head-chat">
        <div class="user">
            <i class="fas fa-arrow-right back-arrow" @click="$emit('back')"></i>
            <img :src="getChatImage(selectedChat)" alt="Avatar" />
            <span class="name">{{ getChatName(selectedChat) }}</span>
        </div>
        <!-- <div class="head-icons">
            <i class="fa-solid fa-phone" @click="callContact"></i>
            <i class="fas fa-video" @click="callContact"></i>
        </div> -->
    </div>
</template>

<script lang="ts">
export default {
    props: {
        selectedChat: {
            type: Object,
            default: null,
        },
    },
    methods: {
        getChatName(chat) {
            console.log(chat);
            if (!chat) return 'اختر محادثة';
            const user = chat.user_id == this.$root.userId ? chat.receiver : chat.creator;
            return user?.name || 'Unknown';
        },
        getChatImage(chat) {
            if (!chat) {
                return 'https://ui-avatars.com/api/?background=0D8ABC&color=fff&name=Unknown';
            }
            const user = chat.user_id == this.$root.userId ? chat.receiver : chat.creator;
            const name = user?.name || user?.username || 'Unknown';
            return user?.image ?? `https://ui-avatars.com/api/?background=0D8ABC&color=fff&name=${encodeURIComponent(name)}`;
        },

    },
};
</script>
