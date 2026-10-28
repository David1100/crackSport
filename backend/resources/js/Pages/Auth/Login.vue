<template>
    <GuestLayout>
        <div class="mb-6 flex justify-center">
            <img src="/logo_principal.webp" alt="CRS Sport" class="h-16 w-auto" />
        </div>

        <form @submit.prevent="submit">
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
            <button type="submit" class="w-full bg-black text-white py-2 rounded" :disabled="form.processing">Iniciar sesión</button>
        </form>
        <p class="mt-4 text-sm">
            <Link href="/forgot-password">¿Olvidaste tu contraseña?</Link>
        </p>
    </GuestLayout>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';

const form = useForm({ email: '', password: '', remember: false });
const submit = () => form.post('/login');
</script>




