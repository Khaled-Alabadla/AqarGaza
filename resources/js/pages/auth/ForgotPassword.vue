<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

defineProps<{
    status?: string;
}>();

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <AuthLayout title="نسيت كلمة المرور؟" description="
أدخل بريدك الإلكتروني لتلقي رابط إعادة تعيين كلمة المرور">

        <Head title="نسيت كلمة المرور؟" />

        <div v-if="status" class="mb-4 text-center text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <div class="space-y-6 text-right">
            <form @submit.prevent="submit">
                <div class="grid gap-2">
                    <Label for="email">البريد الإلكتروني</Label>
                    <Input id="email" type="email" name="email" autocomplete="off" v-model="form.email" autofocus
                        class="text-right" placeholder="email@example.com" />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="my-6 flex items-center justify-start">
                    <Button class="w-full" :disabled="form.processing">
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                        رابط إعادة تعيين كلمة المرور
                    </Button>
                </div>
            </form>

            <div class="space-x-1 text-center text-sm text-muted-foreground">
                <span>أو قم بالرجوع إلى </span>
                <TextLink :href="route('login')">تسجيل الدخول</TextLink>
            </div>
        </div>
    </AuthLayout>
</template>
