<template>
    <AuthenticatedLayout title="Pedidos">
        <div class="py-6">
            <div class="max-w-7xl mx-auto">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold">Pedidos</h2>
                    <button @click="openCreate" class="px-4 py-2 bg-black text-white rounded-md text-sm font-medium hover:bg-gray-800">Nuevo</button>
                </div>
                <div v-if="loading" class="flex justify-center py-10">
                    <div class="animate-spin h-8 w-8 border-4 border-black border-t-transparent rounded-full"></div>
                </div>
                <div v-else class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Teléfono</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jugadores</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Abono</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="o in orders" :key="o.id">
                                <td class="px-6 py-4">{{ o.client_name }}</td>
                                <td class="px-6 py-4">{{ o.client_phone }}</td>
                                <td class="px-6 py-4">{{ (o.players || []).length }}</td>
                                <td class="px-6 py-4">{{ o.total }}</td>
                                <td class="px-6 py-4">{{ o.advance_payment }}</td>
                                <td class="px-6 py-4">{{ o.status }}</td>
                                <td class="px-6 py-4 flex gap-2">
                                    <button @click="view(o)" class="text-sm text-blue-600 hover:underline">Ver</button>
                                    <button @click="abonar(o)" class="text-sm text-green-600 hover:underline">Abonar</button>
                                    <button @click="remove(o.id)" class="text-sm text-red-600 hover:underline">Eliminar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <Modal :show="showModal" @close="showModal = false">
            <div class="p-6">
                <h3 class="text-lg font-bold mb-4">Nuevo Pedido</h3>
                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <InputLabel value="Nombre del cliente" />
                        <TextInput v-model="form.client_name" type="text" class="mt-1 block w-full" required />
                    </div>
                    <div>
                        <InputLabel value="Teléfono" />
                        <TextInput v-model="form.client_phone" type="text" class="mt-1 block w-full" />
                    </div>
                    <div>
                        <InputLabel value="Diseños (imágenes)" />
                        <input type="file" multiple accept="image/*" @change="onImages" />
                    </div>
                    <div>
                        <InputLabel value="Jugadores" />
                        <div v-for="(pl, idx) in players" :key="idx" class="grid grid-cols-6 gap-2 mb-2">
                            <TextInput v-model="pl.name" placeholder="Nombre" class="border rounded px-2 py-1" />
                            <TextInput v-model="pl.number" placeholder="Dorsal" class="border rounded px-2 py-1" />
                            <TextInput v-model="pl.size" placeholder="Talla" class="border rounded px-2 py-1" />
                            <select v-model="pl.type" class="border rounded px-2 py-1">
                                <option value="jugador">Jugador</option>
                                <option value="arquero">Arquero</option>
                            </select>
                            <TextInput v-model="pl.details" placeholder="Detalles" class="border rounded px-2 py-1" />
                            <button type="button" @click="players.splice(idx,1)" class="text-red-600" title="Eliminar">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </div>
                        <button type="button" @click="addPlayer" class="text-sm text-blue-600">+ Agregar jugador</button>
                    </div>
                    <div>
                        <InputLabel value="Total" />
                        <TextInput v-model="form.total" type="number" step="0.01" class="mt-1 block w-full" />
                    </div>
                    <div>
                        <InputLabel value="Abono" />
                        <TextInput v-model="form.advance_payment" type="number" step="0.01" class="mt-1 block w-full" />
                    </div>
                    <div>
                        <InputLabel value="Notas" />
                        <textarea v-model="form.notes" class="mt-1 block w-full border rounded px-3 py-2" rows="3"></textarea>
                    </div>
                    <div class="flex justify-end gap-2">
                        <SecondaryButton type="button" @click="showModal = false">Cancelar</SecondaryButton>
                        <PrimaryButton type="submit">Guardar</PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>
        <Modal :show="showDetail" @close="showDetail = false">
            <div class="p-6 space-y-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-bold">{{ detail?.client_name }}</h3>
                    <button @click="showDetail = false" class="text-gray-500 hover:text-black text-xl">&times;</button>
                </div>
                <p class="text-sm text-gray-600">{{ detail?.client_phone }}</p>
                <p class="text-sm font-bold">Total: {{ detail?.total }}</p>
                <p class="text-sm font-bold">Abono: {{ detail?.advance_payment }}</p>
                <div v-if="detail?.images?.length" class="grid grid-cols-3 gap-2">
                    <img v-for="(im,i) in detail.images" :key="i" :src="im.startsWith('http')?im:im" class="w-full aspect-square object-cover rounded" />
                </div>
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left">Nombre</th>
                            <th class="px-3 py-2 text-left">Dorsal</th>
                            <th class="px-3 py-2 text-left">Talla</th>
                            <th class="px-3 py-2 text-left">Tipo</th>
                            <th class="px-3 py-2 text-left">Detalles</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(pl,i) in (detail?.players || [])" :key="i" class="border-t">
                            <td class="px-3 py-2">{{ pl.name }}</td>
                            <td class="px-3 py-2">{{ pl.number }}</td>
                            <td class="px-3 py-2">{{ pl.size }}</td>
                            <td class="px-3 py-2">{{ pl.type }}</td>
                            <td class="px-3 py-2">{{ pl.details }}</td>
                        </tr>
                    </tbody>
                </table>
                <div v-if="detail?.notes" class="text-sm text-gray-600">{{ detail.notes }}</div>
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
import axios from 'axios';
import Swal from 'sweetalert2';
const orders = ref([]);
const loading = ref(true);
const showModal = ref(false);
const showDetail = ref(false);
const detail = ref(null);
const errors = ref({});
const form = ref({ client_name: '', client_phone: '', notes: '', advance_payment: '', total: '' });
const images = ref([]);
const players = ref([]);
const load = async () => { loading.value = true; try { const { data } = await axios.get('/api/orders'); orders.value = data.data ?? data; } finally { loading.value = false; } };
onMounted(load);
const openCreate = () => { showModal.value = true; form.value = { client_name: '', client_phone: '', notes: '', advance_payment: '', total: '' }; images.value = []; players.value = []; errors.value = {}; };
const onImages = (e) => { images.value = Array.from(e.target.files || []); };
const addPlayer = () => { players.value.push({ name: '', number: '', size: '', type: 'jugador', details: '' }); };
const submit = async () => {
    errors.value = {};
    const fd = new FormData();
    fd.append('client_name', form.value.client_name);
    if (form.value.client_phone) fd.append('client_phone', form.value.client_phone);
    if (form.value.notes) fd.append('notes', form.value.notes);
    if (form.value.advance_payment !== '') fd.append('advance_payment', form.value.advance_payment);
    if (form.value.total !== '') fd.append('total', form.value.total);
    images.value.forEach((f) => fd.append('images[]', f));
    players.value.forEach((pl, i) => {
        fd.append(`players[${i}][name]`, pl.name);
        fd.append(`players[${i}][number]`, pl.number);
        fd.append(`players[${i}][size]`, pl.size);
        fd.append(`players[${i}][type]`, pl.type);
        fd.append(`players[${i}][details]`, pl.details);
    });
    try {
        await axios.post('/api/orders', fd, { headers: { 'Content-Type': 'multipart/form-data' } });
        showModal.value = false;
        await load();
        Swal.fire({ icon: 'success', title: 'Guardado', timer: 1500, showConfirmButton: false });
    } catch (e) {
        errors.value = e.response?.data?.errors || {};
    }
};
const view = (o) => { detail.value = o; showDetail.value = true; };
const abonar = async (o) => {
    const { value } = await Swal.fire({ title: 'Abono', input: 'number', inputLabel: 'Monto', showCancelButton: true });
    if (!value) return;
    await axios.put(`/api/orders/${o.id}`, { advance_payment: value });
    await load();
};
const remove = async (id) => {
    const r = await Swal.fire({ title: 'Eliminar?', icon: 'warning', showCancelButton: true, confirmButtonText: 'Si', cancelButtonText: 'No' });
    if (!r.isConfirmed) return;
    await axios.delete(`/api/orders/${id}`);
    await load();
};
</script>
