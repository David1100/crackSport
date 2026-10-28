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
                <div v-else class="bg-white shadow-sm sm:rounded-lg">
                    <div class="space-y-3 md:hidden">
                        <div v-for="u in users" :key="u.id" class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                            <h3 class="break-words text-sm font-semibold text-gray-900">{{ u.name }}</h3>
                            <p class="mt-1 break-words text-sm text-gray-500">{{ u.email }}</p>
                            <p class="mt-1 text-xs text-gray-400">{{ u.created_at }}</p>
                            <div class="mt-4 flex gap-2 border-t border-gray-100 pt-3">
                                <button @click="remove(u.id)" class="flex-1 rounded-lg bg-red-50 px-3 py-2 text-sm font-medium text-red-700">Eliminar</button>
                            </div>
                        </div>
                        <div v-if="!users.length" class="py-8 text-center text-sm italic text-gray-400">Sin usuarios</div>
                    </div>
                    <div class="hidden overflow-x-auto md:block">
                    <table class="min-w-[900px] md:min-w-full divide-y divide-gray-200">
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
                                    <button @click="remove(u.id)" class="text-red-600 hover:text-red-800" title="Eliminar"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
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






