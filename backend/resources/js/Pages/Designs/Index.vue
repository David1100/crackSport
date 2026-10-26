<template>
    <AuthenticatedLayout title="Diseños">
        <div class="py-6">
            <div class="max-w-7xl mx-auto">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold">Diseños</h2>
                    <label class="px-4 py-2 bg-black text-white rounded-md text-sm font-medium hover:bg-gray-800 cursor-pointer">
                        Subir
                        <input type="file" accept="image/*" class="hidden" @change="upload" />
                    </label>
                </div>
                <div v-if="loading" class="flex justify-center py-10">
                    <div class="animate-spin h-8 w-8 border-4 border-black border-t-transparent rounded-full"></div>
                </div>
                <div v-else class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div v-for="d in designs" :key="d.id" class="relative group">
                        <img :src="d.image.startsWith('http')?d.image:d.image" alt="" class="w-full aspect-square object-cover rounded" />
                        <button @click="remove(d.id)" class="absolute top-2 right-2 bg-red-600 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout.vue';
import axios from 'axios';
import Swal from 'sweetalert2';
const designs = ref([]);
const loading = ref(true);
const load = async () => { loading.value = true; try { const { data } = await axios.get('/api/designs'); designs.value = data.data ?? data; } finally { loading.value = false; } };
onMounted(load);
const upload = async (e) => {
    const file = e.target.files?.[0];
    if (!file) return;
    const fd = new FormData();
    fd.append('image', file);
    await axios.post('/api/designs', fd, { headers: { 'Content-Type': 'multipart/form-data' } });
    await load();
    Swal.fire({ icon: 'success', title: 'Subido', timer: 1200, showConfirmButton: false });
};
const remove = async (id) => {
    const r = await Swal.fire({ title: 'Eliminar?', icon: 'warning', showCancelButton: true, confirmButtonText: 'Si', cancelButtonText: 'No' });
    if (!r.isConfirmed) return;
    await axios.delete(`/api/designs/${id}`);
    await load();
};
</script>
