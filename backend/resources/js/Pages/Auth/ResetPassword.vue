<template>
    <GuestLayout>
        <h2 class="text-xl font-bold mb-4">Reset Password</h2>
        <form @submit.prevent="submit">
            <input type="hidden" v-model="form.token" />
            <div class="mb-4">
                <label class="block text-sm font-medium">Email</label>
                <input v-model="form.email" type="email" class="mt-1 w-full border rounded px-3 py-2" required />
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Password</label>
                <input v-model="form.password" type="password" class="mt-1 w-full border rounded px-3 py-2" required />
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Confirm Password</label>
                <input v-model="form.password_confirmation" type="password" class="mt-1 w-full border rounded px-3 py-2" required />
            </div>
            <button type="submit" class="w-full bg-black text-white py-2 rounded" :disabled="form.processing">Reset Password</button>
        </form>
    </GuestLayout>
</template>

<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';

const page = usePage();
const form = useForm({
    token: page.props.token,
    email: page.props.email,
    password: '',
    password_confirmation: '',
});
const submit = () => form.post('/reset-password');
</script>



