<template>
    <GuestLayout>
        <h2 class="text-xl font-bold mb-4">Register</h2>
        <form @submit.prevent="submit">
            <div class="mb-4">
                <label class="block text-sm font-medium">Name</label>
                <input v-model="form.name" type="text" class="mt-1 w-full border rounded px-3 py-2" required />
                <div v-if="form.errors.name" class="text-red-600 text-sm">{{ form.errors.name }}</div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Email</label>
                <input v-model="form.email" type="email" class="mt-1 w-full border rounded px-3 py-2" required />
                <div v-if="form.errors.email" class="text-red-600 text-sm">{{ form.errors.email }}</div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Password</label>
                <input v-model="form.password" type="password" class="mt-1 w-full border rounded px-3 py-2" required />
                <div v-if="form.errors.password" class="text-red-600 text-sm">{{ form.errors.password }}</div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Confirm Password</label>
                <input v-model="form.password_confirmation" type="password" class="mt-1 w-full border rounded px-3 py-2" required />
            </div>
            <button type="submit" class="w-full bg-black text-white py-2 rounded" :disabled="form.processing">Register</button>
        </form>
    </GuestLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';

const form = useForm({ name: '', email: '', password: '', password_confirmation: '' });
const submit = () => form.post('/register');
</script>




