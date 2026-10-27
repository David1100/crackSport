<template>
    <AuthenticatedLayout title="Telas">
        <div class="py-6">
            <div class="max-w-7xl mx-auto">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold">Telas</h2>
                    <button @click="openCreate" class="px-4 py-2 bg-black text-white rounded-md text-sm font-medium hover:bg-gray-900">Nuevo</button>
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="t in telas" :key="t.id">
                                <td class="px-6 py-4">
                                    <img v-if="t.main_photo" :src="t.main_photo" class="h-12 w-12 object-cover rounded" />
                                </td>
                                <td class="px-6 py-4">{{ t.name }}</td>
                                <td class="px-6 py-4 flex gap-2">
                                    <button @click="openEdit(t)" class="text-blue-600 hover:text-blue-800" title="Editar"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg></button>
                                    <button @click="remove(t.id)" class="text-red-600 hover:text-red-800" title="Eliminar"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <Modal :show="showModal" @close="closeModal">
            <div class="p-6">
                <h3 class="text-lg font-bold mb-4">{{ editing ? 'Editar Tela' : 'Nueva Tela' }}</h3>
                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <InputLabel value="Nombre" />
                        <TextInput v-model="form.name" type="text" class="mt-1 block w-full" required />
                        <InputError :message="errors.name" />
                    </div>
                    <div>
                        <InputLabel value="Descripcion" />
                        <WysiwygEditor v-model="form.description" />
                        <InputError :message="errors.description" />
                    </div>
                    <div>
                        <InputLabel value="Foto principal" />
                        <img v-if="mainPhotoPreview" :src="mainPhotoPreview" class="h-20 w-20 object-cover rounded mb-2" />
                        <ImageUpload v-model:file="mainPhoto" v-model:preview="mainPhotoPreview" />
                    </div>
                    <div>
                        <InputLabel value="Galeria" />
                        <input type="file" multiple accept="image/*" @change="onPhotos" />
                        <div class="flex gap-2 mt-2 flex-wrap">
                            <div v-for="(p,i) in gallery" :key="i" class="relative">
                                <img :src="p" class="h-16 w-16 object-cover rounded" />
                                <button type="button" @click="deletePhoto(p)" class="absolute -top-1 -right-1 bg-red-600 text-white rounded-full w-5 h-5 text-xs">x</button>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2">
                        <SecondaryButton type="button" @click="closeModal">Cancelar</SecondaryButton>
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
import WysiwygEditor from '../../Components/WysiwygEditor.vue';
import InputLabel from '../../Components/InputLabel.vue';
import InputError from '../../Components/InputError.vue';
import PrimaryButton from '../../Components/PrimaryButton.vue';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import ImageUpload from '../../Components/ImageUpload.vue';
import Swal from 'sweetalert2';
const telas = ref([]);
const loading = ref(true);
const showModal = ref(false);
const editing = ref(null);
const errors = ref({});
const form = ref({ name: '', description: '' });
const mainPhoto = ref(null);
const mainPhotoPreview = ref(null);
const photos = ref([]);
const photoPreviews = ref([]);
const gallery = ref([]);
const onPhotos = (e) => {
    photos.value = Array.from(e.target.files || []);
    photoPreviews.value = [];
    photos.value.forEach(f => {
        const r = new FileReader();
        r.onload = (ev) => photoPreviews.value.push(ev.target.result);
        r.readAsDataURL(f);
    });
};
const load = async () => { loading.value = true; try { const { data } = await axios.get('/api/telas'); telas.value = data.data ?? data; } finally { loading.value = false; } };
onMounted(load);
const openCreate = () => {
    editing.value = null;
    form.value = { name: '', description: '' };
    mainPhoto.value = null; mainPhotoPreview.value = null;
    photos.value = []; photoPreviews.value = []; gallery.value = [];
    showModal.value = true;
};
const openEdit = (t) => {
    editing.value = t.id;
    form.value = { name: t.name, description: t.description || '' };
    mainPhoto.value = null; mainPhotoPreview.value = t.main_photo || null;
    photos.value = []; photoPreviews.value = [];
    gallery.value = t.photos || [];
    showModal.value = true;
};
const closeModal = () => { showModal.value = false; };
const deletePhoto = async (p) => {
    if (!editing.value) return;
    await axios.delete(`/api/telas/${editing.value}/photos`, { data: { photo: p } });
    gallery.value = gallery.value.filter(x => x !== p);
};
const submit = async () => {
    errors.value = {};
    const fd = new FormData();
    fd.append('name', form.value.name);
    if (form.value.description) fd.append('description', form.value.description);
    if (mainPhoto.value) fd.append('main_photo', mainPhoto.value);
    photos.value.forEach(f => fd.append('photos[]', f));
    try {
        if (editing.value) {
            await axios.post(`/api/telas/${editing.value}` , fd, { params: { _method: 'PUT' } });
        } else {
            await axios.post('/api/telas', fd);
        }
        closeModal();
        await load();
    } catch (e) {
        errors.value = e.response?.data?.errors || {};
    }
};
const remove = async (id) => {
    const { isConfirmed } = await Swal.fire({ title: 'Eliminar?', icon: 'warning', showCancelButton: true });
    if (!isConfirmed) return;
    await axios.delete(`/api/telas/${id}`);
    await load();
};
</script>





