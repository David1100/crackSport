<template>
    <AuthenticatedLayout title="Nuevo producto">
        <div class="py-6 max-w-2xl mx-auto">
            <h2 class="text-xl font-bold mb-4">Nuevo producto</h2>
            <form @submit.prevent="submit" class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
                <div><InputLabel for="name" value="Nombre" /><TextInput id="name" v-model="form.name" class="mt-1 block w-full" required /><InputError :message="form.errors.name" class="mt-2" /></div>
                <div><InputLabel for="category_id" value="Categoría" />
                    <select id="category_id" v-model="form.category_id" class="mt-1 block w-full border-gray-300 rounded-md"><option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option></select>
                </div>
                <div><InputLabel for="price" value="Precio" /><TextInput id="price" type="number" v-model="form.price" class="mt-1 block w-full" required /></div>
                <div><InputLabel for="discount_price" value="Precio descuento" /><TextInput id="discount_price" type="number" v-model="form.discount_price" class="mt-1 block w-full" /></div>
                <div><InputLabel for="image" value="Imagen (URL)" /><TextInput id="image" v-model="form.image" class="mt-1 block w-full" /></div>
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
const props = defineProps({ categories: Array });
const form = useForm({ name: '', category_id: null, price: '', discount_price: '', image: '' });
const submit = () => form.post(route('products.store'));
</script>



