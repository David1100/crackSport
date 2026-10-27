<template>
    <AuthenticatedLayout title="Combos">
        <div class="py-6">
            <div class="max-w-7xl mx-auto">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold">Combos</h2>
                    <button @click="openCreate" class="px-4 py-2 bg-black text-white rounded-md text-sm font-medium hover:bg-gray-800">Nuevo</button>
                </div>
                <div v-if="loading" class="flex justify-center py-10">
                    <div class="animate-spin h-8 w-8 border-4 border-black border-t-transparent rounded-full"></div>
                </div>
                <div v-else class="bg-white shadow-sm sm:rounded-lg overflow-hidden overflow-x-auto w-full">
                    <table class="min-w-[640px] md:min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Foto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Precio</th>


                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="c in combos" :key="c.id">
                                <td class="px-6 py-4"><img v-if="c.photo" :src="c.photo.startsWith('http')?c.photo:c.photo" alt="" class="h-10 w-10 object-cover rounded" /></td>
                                <td class="px-6 py-4">{{ c.name }}</td>
                                <td class="px-6 py-4">{{ c.price }}</td>


                                <td class="px-6 py-4 flex gap-2">
                                    <button @click="openEdit(c)" class="text-blue-600 hover:text-blue-800" title="Editar"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg></button>
                                    <button @click="remove(c.id)" class="text-red-600 hover:text-red-800" title="Eliminar"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <Modal :show="showModal" @close="showModal = false">
            <div class="p-6">
                <h3 class="text-lg font-bold mb-4">{{ editing ? 'Editar' : 'Nuevo' }} Combo</h3>
                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <InputLabel value="Nombre" />
                        <TextInput v-model="form.name" type="text" class="mt-1 block w-full" required />
                        <InputError :message="errors.name" />
                    </div>
                    <div>
                        <InputLabel value="Precio" />
                        <TextInput v-model="form.price" type="number" step="0.01" class="mt-1 block w-full" />
                    </div>
                    <div>
                        <InputLabel value="Que incluye" />
                        <WysiwygEditor v-model="form.includes" />
                    </div>
                    <div>
                        <InputLabel value="Aplica para" />
                        <TextInput v-model="form.applies_for" type="text" class="mt-1 block w-full" placeholder="Ej: 12 unidades en adelante" />
                    </div>
                    <div>
                        <InputLabel value="Foto" />
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
import WysiwygEditor from '../../Components/WysiwygEditor.vue';
import axios from 'axios';
import Swal from 'sweetalert2';
const combos = ref([]);
const loading = ref(true);
const showModal = ref(false);
const editing = ref(null);
const errors = ref({});
const form = ref({ name: '', price: '', includes: '', applies_for: '' });
const file = ref(null);
const preview = ref(null);
const load = async () => { loading.value = true; try { const { data } = await axios.get('/api/combos'); combos.value = data.data ?? data; } finally { loading.value = false; } };
onMounted(load);
const openCreate = () => { editing.value = null; form.value = { name: '', price: '', includes: '', applies_for: '' }; file.value = null; preview.value = null; errors.value = {}; showModal.value = true; };
const openEdit = (c) => { editing.value = c.id; form.value = { name: c.name, price: c.price, includes: c.includes, applies_for: c.applies_for }; file.value = null; preview.value = c.photo ? (c.photo.startsWith('http')?c.photo:c.photo) : null; errors.value = {}; showModal.value = true; };
const submit = async () => {
    errors.value = {};
    const fd = new FormData();
    fd.append('name', form.value.name);
    if (form.value.price !== '') fd.append('price', form.value.price);
    if (form.value.includes) fd.append('includes', form.value.includes);
    if (form.value.applies_for) fd.append('applies_for', form.value.applies_for);
    if (file.value) fd.append('photo', file.value);
    try {
        if (editing.value) await axios.post(`/api/combos/${editing.value}`, fd, { params: { _method: 'PUT' }, headers: { 'Content-Type': 'multipart/form-data' } });
        else await axios.post('/api/combos', fd, { headers: { 'Content-Type': 'multipart/form-data' } });
        showModal.value = false;
        form.value = { name: '', price: '', includes: '', applies_for: '' };
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
    const r = await Swal.fire({ title: 'Eliminar?', icon: 'warning', showCancelButton: true, confirmButtonText: 'Si', cancelButtonText: 'No' });
    if (!r.isConfirmed) return;
    await axios.delete(`/api/combos/${id}`);
    await load();
    Swal.fire({ icon: 'success', title: 'Eliminado', timer: 1500, showConfirmButton: false });
};
</script>






