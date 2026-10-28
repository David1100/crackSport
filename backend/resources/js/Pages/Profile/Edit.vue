<template>
    <AuthenticatedLayout>
        <h2 class="text-xl font-bold mb-4">Profile</h2>

        <section class="mb-8">
            <h3 class="text-lg font-semibold mb-2">Profile Information</h3>
            <form @submit.prevent="profileForm.patch(route('profile.update'))" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium">Name</label>
                    <input v-model="profileForm.name" type="text" class="mt-1 w-full border rounded px-3 py-2" />
                    <div v-if="profileForm.errors.name" class="text-red-600 text-sm">{{ profileForm.errors.name }}</div>
                </div>
                <div>
                    <label class="block text-sm font-medium">Email</label>
                    <input v-model="profileForm.email" type="email" class="mt-1 w-full border rounded px-3 py-2" />
                    <div v-if="profileForm.errors.email" class="text-red-600 text-sm">{{ profileForm.errors.email }}</div>
                </div>
                <button type="submit" class="bg-black text-white px-4 py-2 rounded" :disabled="profileForm.processing">Save</button>
            </form>
        </section>

        <section class="mb-8">
            <h3 class="text-lg font-semibold mb-2">Update Password</h3>
            <form @submit.prevent="passwordForm.put(route('password.update'))" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium">Current Password</label>
                    <input v-model="passwordForm.current_password" type="password" class="mt-1 w-full border rounded px-3 py-2" />
                    <div v-if="passwordForm.errors.current_password" class="text-red-600 text-sm">{{ passwordForm.errors.current_password }}</div>
                </div>
                <div>
                    <label class="block text-sm font-medium">New Password</label>
                    <input v-model="passwordForm.password" type="password" class="mt-1 w-full border rounded px-3 py-2" />
                    <div v-if="passwordForm.errors.password" class="text-red-600 text-sm">{{ passwordForm.errors.password }}</div>
                </div>
                <div>
                    <label class="block text-sm font-medium">Confirm Password</label>
                    <input v-model="passwordForm.password_confirmation" type="password" class="mt-1 w-full border rounded px-3 py-2" />
                </div>
                <button type="submit" class="bg-black text-white px-4 py-2 rounded" :disabled="passwordForm.processing">Save</button>
            </form>
        </section>

        <section>
            <h3 class="text-lg font-semibold mb-2">Delete Account</h3>
            <form @submit.prevent="deleteForm.delete(route('profile.destroy'))" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium">Password</label>
                    <input v-model="deleteForm.password" type="password" class="mt-1 w-full border rounded px-3 py-2" />
                    <div v-if="deleteForm.errors.password" class="text-red-600 text-sm">{{ deleteForm.errors.password }}</div>
                </div>
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded" :disabled="deleteForm.processing">Delete Account</button>
            </form>
        </section>
    </AuthenticatedLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({ auth: Object, mustVerifyEmail: Boolean, status: String });

const profileForm = useForm({
    name: props.auth?.user?.name || '',
    email: props.auth?.user?.email || '',
});

const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const deleteForm = useForm({
    password: '',
});
</script>




