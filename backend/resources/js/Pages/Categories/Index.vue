<template>
    <AuthenticatedLayout title="Categorías">
        <div class="py-6">
            <div class="max-w-7xl mx-auto">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold">Categorías</h2>
                    <button @click="openCreate" class="px-4 py-2 bg-black text-white rounded-md text-sm font-medium hover:bg-gray-800">Nuevo</button>
                </div>
                <div v-if="loading" class="flex justify-center py-10">
                    <div class="animate-spin h-8 w-8 border-4 border-black border-t-transparent rounded-full"></div>
                </div>
                <div v-else class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Imagen</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="c in categories" :key="c.id">
                                <td class="px-6 py-4"><img v-if="c.image" :src="c.image.startsWith('http')?c.image:'/storage/'+c.image" alt="" class="h-10 w-10 object-cover rounded" /></td>
                                <td class="px-6 py-4">{{ c.name }}</td>
                                <td class="px-6 py-4 flex gap-2">
                                    <button @click="openEdit(c)" class="text-sm text-blue-600 hover:underline">Editar</button>
                                    <button @click="remove(c.id)" class="text-sm text-red-600 hover:underline">Eliminar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <Modal :show="showModal" @close="showModal = false">
            <div class="p-6">
                <h3 class="text-lg font-bold mb-4">{{ editing ? 'Editar' : 'Nueva' }} Categoría</h3>
                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <InputLabel value="Nombre" />
                        <TextInput v-model="form.name" type="text" class="mt-1 block w-full" required />
                        <InputError :message="errors.name" />
                    </div>
                    <div>
                        <InputLabel value="Imagen" />
                        <ImageUpload v-model:file="file" v-model:preview="preview" />
                    </div>
                    <div class="flex justify-end gap-2">
                        <SecondaryButton type="button" @click="showModal = false">Cancelar</SecondaryButton>
                        <PrimaryButton type="submit">Guardar</PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
<script setup>
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue';
import Modal from '../../Components/Modal.vue';
import TextInput from '../../Components/TextInput.vue';
import InputLabel from '../../Components/InputLabel.vue';
import InputError from '../../Components/InputError.vue';
import PrimaryButton from '../../Components/PrimaryButton.vue';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import ImageUpload from '../../Components/ImageUpload.vue';
import axios from 'axios';
import Swal from 'sweetalert2';
const categories = ref([]);
const loading = ref(true);
const showModal = ref(false);
const editing = ref(null);
const errors = ref({});
const form = ref({ name: '' });
const file = ref(null);
const preview = ref(null);
const onFile = (e) => { file.value = e.target.files[0]; };
const load = async () => { loading.value = true; try { const { data } = await axios.get('/api/categories'); categories.value = data.data ?? data; } finally { loading.value = false; } };
onMounted(load);
const openCreate = () => { editing.value = null; form.value = { name: '' }; file.value = null; preview.value = null; errors.value = {}; showModal.value = true; };
const openEdit = (c) => { editing.value = c.id; form.value = { name: c.name }; file.value = null; preview.value = c.image ? (c.image.startsWith('http')?c.image:'/storage/'+c.image) : null; errors.value = {}; showModal.value = true; };
const submit = async () => {
    errors.value = {};
    const fd = new FormData();
    fd.append('name', form.value.name);
    if (file.value) fd.append('image', file.value);
    try {
        if (editing.value) await axios.post(`/api/categories/${editing.value}`, fd, { params: { _method: 'PUT' }, headers: { 'Content-Type': 'multipart/form-data' } });
        else await axios.post('/api/categories', fd, { headers: { 'Content-Type': 'multipart/form-data' } });
        showModal.value = false;
        form.value = { name: '' };
        file.value = null;
        preview.value = null;
        editing.value = null;
        await load();
        Swal.fire({ icon: 'success', title: 'Guardado', timer: 1500, showConfirmButton: false });
    } catch (e) {
        errors.value = e.response?.data?.errors || {};
    }
};
const remove = async (id) => {
    const r = await Swal.fire({ title: '¿Eliminar?', icon: 'warning', showCancelButton: true, confirmButtonText: 'Sí', cancelButtonText: 'No' });
    if (!r.isConfirmed) return;
    await axios.delete(`/api/categories/${id}`);
    await load();
    Swal.fire({ icon: 'success', title: 'Eliminado', timer: 1500, showConfirmButton: false });
};
</script>