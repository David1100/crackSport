<template>
    <GuestLayout>
        <h2 class="text-xl font-bold mb-4">Verify Email</h2>
        <p class="mb-4 text-sm text-gray-600">Please verify your email address before continuing.</p>
        <form @submit.prevent="submit" class="mb-4">
            <button type="submit" class="w-full bg-black text-white py-2 rounded" :disabled="form.processing">Resend Verification Email</button>
        </form>
        <form method="POST" action="/logout">
            <button type="submit" class="text-sm text-gray-600 underline">Log out</button>
        </form>
    </GuestLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';

const form = useForm({});
const submit = () => form.post('/email/verification-notification');
</script>




