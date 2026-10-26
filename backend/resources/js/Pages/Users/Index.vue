<template>
    <AuthenticatedLayout title="Usuarios">
        <div class="py-6">
            <div class="max-w-7xl mx-auto">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold">Usuarios</h2>
                    <button @click="showModal = true" class="px-4 py-2 bg-black text-white rounded-md text-sm font-medium hover:bg-gray-900">Nuevo</button>
                </div>
                <div v-if="loading" class="flex justify-center py-10">
                    <div class="animate-spin h-8 w-8 border-4 border-black border-t-transparent rounded-full"></div>
                </div>
                <div v-else class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Creado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="u in users" :key="u.id">
                                <td class="px-6 py-4 whitespace-nowrap">{{ u.name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ u.email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ formatDate(u.created_at) }}</td>
                                <td class="px-6 py-4 flex gap-2">
                                    <button @click="remove(u.id)" class="text-sm text-red-600 hover:underline">Eliminar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <Modal :show="showModal" @close="showModal = false">
            <div class="p-6">
                <h3 class="text-lg font-bold mb-4">Nuevo Usuario</h3>
                <form @submit.prevent="create" class="space-y-4">
                    <div>
                        <InputLabel value="Nombre" />
                        <TextInput v-model="form.name" type="text" class="mt-1 block w-full" required />
                        <InputError :message="errors.name" />
                    </div>
                    <div>
                        <InputLabel value="Email" />
                        <TextInput v-model="form.email" type="email" class="mt-1 block w-full" required />
                        <InputError :message="errors.email" />
                    </div>
                    <div>
                        <InputLabel value="Password" />
                        <TextInput v-model="form.password" type="password" class="mt-1 block w-full" required />
                        <InputError :message="errors.password" />
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
import Swal from 'sweetalert2';
const users = ref([]);
const loading = ref(true);
const showModal = ref(false);
const errors = ref({});
const form = ref({ name: '', email: '', password: '' });
const load = async () => { loading.value = true; try { const { data } = await axios.get('/api/users'); users.value = data.data ?? data; } finally { loading.value = false; } };
onMounted(load);
const formatDate = (d) => d ? new Date(d).toLocaleDateString('es-ES') : '';
const create = async () => {
    errors.value = {};
    try {
        await axios.post('/api/users', form.value);
        showModal.value = false;
        form.value = { name: '', email: '', password: '' };
        await load();
    } catch (e) {
        errors.value = e.response?.data?.errors || {};
    }
};
const remove = async (id) => {
    const { isConfirmed } = await Swal.fire({ title: 'Eliminar?', icon: 'warning', showCancelButton: true });
    if (!isConfirmed) return;
    await axios.delete(`/api/users/${id}`);
    await load();
};
</script>