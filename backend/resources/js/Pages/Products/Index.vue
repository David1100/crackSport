<template>
    <AuthenticatedLayout title="Productos">
        <div class="py-6">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                    <h2 class="text-xl font-bold">Productos</h2>
                    <div class="flex items-center gap-2">
                        <input v-model="search" type="text" placeholder="Buscar producto o categoría..." class="border rounded px-2 py-1.5 text-sm pr-8" />
                        <button @click="openCreate" class="px-4 py-2 bg-black text-white rounded-md text-sm font-medium hover:bg-gray-800">Nuevo</button>
                    </div>
                </div>
                <div v-if="loading" class="flex justify-center py-10">
                    <div class="animate-spin h-8 w-8 border-4 border-black border-t-transparent rounded-full"></div>
                </div>
                <div v-else class="bg-white shadow-sm sm:rounded-lg">
                    <div class="space-y-3 md:hidden">
                        <div v-for="p in filteredProducts" :key="p.id" class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                            <div class="flex items-center gap-3">
                                <img v-if="p.image" :src="p.image.startsWith('http')?p.image:p.image" alt="" class="h-12 w-12 object-cover rounded" />
                                <div class="min-w-0 flex-1">
                                    <h3 class="break-words text-sm font-semibold text-gray-900">{{ p.name }}</h3>
                                    <p class="mt-1 text-xs text-gray-500">{{ p.category?.name }}</p>
                                    <p class="mt-1 text-sm font-semibold text-gray-900">{{ p.price }}</p>
                                </div>
                            </div>
                            <div class="mt-4 flex gap-2 border-t border-gray-100 pt-3">
                                <button @click="openEdit(p)" class="flex-1 rounded-lg bg-blue-50 px-3 py-2 text-sm font-medium text-blue-700">Editar</button>
                                <button @click="remove(p.id)" class="flex-1 rounded-lg bg-red-50 px-3 py-2 text-sm font-medium text-red-700">Eliminar</button>
                            </div>
                        </div>
                        <div v-if="!filteredProducts.length" class="py-8 text-center text-sm italic text-gray-400">Sin productos</div>
                    </div>
                    <div class="hidden overflow-x-auto md:block">
                    <table class="min-w-[900px] md:min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Imagen</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Categoría</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Precio</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="p in filteredProducts" :key="p.id">
                                <td class="px-6 py-4"><img v-if="p.image" :src="p.image.startsWith('http')?p.image:p.image" alt="" class="h-10 w-10 object-cover rounded" /></td>
                                <td class="px-6 py-4">{{ p.name }}</td>
                                <td class="px-6 py-4"><Link :href="`/categories`" class="text-blue-600 hover:underline">{{ p.category?.name }}</Link></td>
                                <td class="px-6 py-4">{{ p.price }}</td>
                                <td class="px-6 py-4 flex gap-2">
                                    <button @click="openEdit(p)" class="text-blue-600 hover:text-blue-800" title="Editar"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg></button>
                                    <button @click="remove(p.id)" class="text-red-600 hover:text-red-800" title="Eliminar"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        </div>
        <Modal :show="showModal" @close="showModal = false">
            <div class="p-6">
                <h3 class="text-lg font-bold mb-4">{{ editing ? 'Editar' : 'Nuevo' }} Producto</h3>
                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <InputLabel value="Nombre" />
                        <TextInput v-model="form.name" type="text" class="mt-1 block w-full" required />
                        <InputError :message="errors.name" />
                    </div>
                    <div>
                        <InputLabel value="Precio" />
                        <TextInput v-model="form.price" type="number" class="mt-1 block w-full" required />
                        <InputError :message="errors.price" />
                    </div>
                    <div>
                        <InputLabel value="Categoría" />
                        <select v-model="form.category_id" class="mt-1 block w-full border-gray-300 rounded-md" required>
                            <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
                        </select>
                        <InputError :message="errors.category_id" />
                    </div>
                    <div>
                        <InputLabel value="Imagen" />
                        <ImageUpload v-model:file="file" v-model:preview="preview" />
                    </div>
                    <div>
                        <InputLabel value="Galeria de imagenes" />
                        <input type="file" multiple accept="image/*" @change="onImages" />
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
import { ref, onMounted, computed } from 'vue';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue';
import Modal from '../../Components/Modal.vue';
import TextInput from '../../Components/TextInput.vue';
import InputLabel from '../../Components/InputLabel.vue';
import InputError from '../../Components/InputError.vue';
import PrimaryButton from '../../Components/PrimaryButton.vue';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import ImageUpload from '../../Components/ImageUpload.vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';
import Swal from 'sweetalert2';
const products = ref([]);
const search = ref('');
const filteredProducts = computed(() => {
    const q = search.value.trim().toLowerCase();
    if (!q) return products.value;
    return products.value.filter(p => (p.name || '').toLowerCase().includes(q) || (p.category?.name || '').toLowerCase().includes(q));
});
const loading = ref(true);
const categories = ref([]);
const showModal = ref(false);
const editing = ref(null);
const errors = ref({});
const form = ref({ name: '', price: '', category_id: '' });
const file = ref(null);
const preview = ref(null);
const gallery = ref([]);
const onImages = (e) => { gallery.value = Array.from(e.target.files || []); };
const load = async () => { loading.value = true; try { const { data: p } = await axios.get('/api/products'); products.value = p.data ?? p; const { data: c } = await axios.get('/api/categories'); categories.value = c.data ?? c; } finally { loading.value = false; } };
onMounted(load);
const openCreate = () => { editing.value = null; form.value = { name: '', price: '', category_id: '' }; file.value = null; preview.value = null; errors.value = {}; showModal.value = true; };
const openEdit = (p) => { editing.value = p.id; form.value = { name: p.name, price: p.price, category_id: p.category_id ?? p.category?.id }; file.value = null; preview.value = p.image ? (p.image.startsWith('http')?p.image:p.image) : null; errors.value = {}; showModal.value = true; };
const submit = async () => {
    errors.value = {};
    const fd = new FormData();
    fd.append('name', form.value.name);
    fd.append('price', form.value.price);
    fd.append('category_id', form.value.category_id);
    if (file.value) fd.append('image', file.value);
    gallery.value.forEach((f) => fd.append('images[]', f));
    try {
        if (editing.value) await axios.post(`/api/products/${editing.value}`, fd, { params: { _method: 'PUT' }, headers: { 'Content-Type': 'multipart/form-data' } });
        else await axios.post('/api/products', fd, { headers: { 'Content-Type': 'multipart/form-data' } });
        showModal.value = false;
        form.value = { name: '', price: '', category_id: '' };
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
    await axios.delete(`/api/products/${id}`);
    await load();
    Swal.fire({ icon: 'success', title: 'Eliminado', timer: 1500, showConfirmButton: false });
};
</script>






