<template>
    <AuthenticatedLayout title="Editar usuario">
        <div class="py-6 max-w-2xl mx-auto">
            <h2 class="text-xl font-bold mb-4">Editar usuario</h2>
            <form @submit.prevent="submit" class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
                <div>
                    <InputLabel for="name" value="Nombre" />
                    <TextInput id="name" v-model="form.name" class="mt-1 block w-full" required />
                    <InputError :message="form.errors.name" class="mt-2" />
                </div>
                <div>
                    <InputLabel for="email" value="Email" />
                    <TextInput id="email" type="email" v-model="form.email" class="mt-1 block w-full" required />
                    <InputError :message="form.errors.email" class="mt-2" />
                </div>
                <div>
                    <InputLabel for="password" value="Contraseña (opcional)" />
                    <TextInput id="password" type="password" v-model="form.password" class="mt-1 block w-full" />
                    <InputError :message="form.errors.password" class="mt-2" />
                </div>
                <div>
                    <InputLabel for="password_confirmation" value="Confirmar contraseña" />
                    <TextInput id="password_confirmation" type="password" v-model="form.password_confirmation" class="mt-1 block w-full" />
                </div>
                <div class="flex justify-end gap-2">
                    <PrimaryButton :disabled="form.processing">Guardar</PrimaryButton>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue';
import TextInput from '../../Components/TextInput.vue';
import InputLabel from '../../Components/InputLabel.vue';
import InputError from '../../Components/InputError.vue';
import PrimaryButton from '../../Components/PrimaryButton.vue';
import { useForm } from '@inertiajs/vue3';
const props = defineProps({ user: Object });
const form = useForm({ name: props.user.name, email: props.user.email, password: '', password_confirmation: '' });
const submit = () => form.put(route('users.update', props.user.id));
</script>




