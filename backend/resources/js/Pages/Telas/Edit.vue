<template>
    <AuthenticatedLayout title="Editar tela">
        <div class="py-6 max-w-2xl mx-auto">
            <h2 class="text-xl font-bold mb-4">Editar tela</h2>
            <form @submit.prevent="submit" class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
                <div><InputLabel for="name" value="Nombre" /><TextInput id="name" v-model="form.name" class="mt-1 block w-full" required /><InputError :message="form.errors.name" class="mt-2" /></div>
                <div><InputLabel for="main_photo" value="Foto principal (URL)" /><TextInput id="main_photo" v-model="form.main_photo" class="mt-1 block w-full" /></div>
                <div class="flex justify-end"><PrimaryButton :disabled="form.processing">Guardar</PrimaryButton></div>
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
const props = defineProps({ tela: Object });
const form = useForm({ name: props.tela.name, main_photo: props.tela.main_photo });
const submit = () => form.put(route('telas.update', props.tela.id));
</script>




