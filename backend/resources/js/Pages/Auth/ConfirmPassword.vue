<template>
    <GuestLayout>
        <h2 class="text-xl font-bold mb-4">Confirm Password</h2>
        <form @submit.prevent="submit">
            <div class="mb-4">
                <label class="block text-sm font-medium">Password</label>
                <input v-model="form.password" type="password" class="mt-1 w-full border rounded px-3 py-2" required />
                <div v-if="form.errors.password" class="text-red-600 text-sm">{{ form.errors.password }}</div>
            </div>
            <button type="submit" class="w-full bg-black text-white py-2 rounded" :disabled="form.processing">Confirm</button>
        </form>
    </GuestLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';

const form = useForm({ password: '' });
const submit = () => form.post('/confirm-password');
</script>




