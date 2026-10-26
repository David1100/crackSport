<template>
    <GuestLayout>
        <h2 class="text-xl font-bold mb-4">Forgot Password</h2>
        <form @submit.prevent="submit">
            <div class="mb-4">
                <label class="block text-sm font-medium">Email</label>
                <input v-model="form.email" type="email" class="mt-1 w-full border rounded px-3 py-2" required />
                <div v-if="form.errors.email" class="text-red-600 text-sm">{{ form.errors.email }}</div>
            </div>
            <button type="submit" class="w-full bg-black text-white py-2 rounded" :disabled="form.processing">Send Reset Link</button>
        </form>
    </GuestLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';

const form = useForm({ email: '' });
const submit = () => form.post('/forgot-password');
</script>