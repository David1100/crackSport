<template>
    <AuthenticatedLayout title="Dashboard">
        <div class="py-6">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
                    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-700 p-6 text-white shadow-lg">
                        <div class="absolute -right-6 -top-6 w-24 h-24 bg-white/10 rounded-full"></div>
                        <p class="text-sm font-medium opacity-90">Ganancias</p>
                        <p class="mt-2 text-3xl font-bold">{{ formatPrice(earnings) }}</p>
                    </div>
                    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 p-6 text-white shadow-lg">
                        <div class="absolute -right-6 -top-6 w-24 h-24 bg-white/10 rounded-full"></div>
                        <p class="text-sm font-medium opacity-90">Pedidos pendientes</p>
                        <p class="mt-2 text-3xl font-bold">{{ pending }}</p>
                    </div>
                    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-700 p-6 text-white shadow-lg">
                        <div class="absolute -right-6 -top-6 w-24 h-24 bg-white/10 rounded-full"></div>
                        <p class="text-sm font-medium opacity-90">Total productos</p>
                        <p class="mt-2 text-3xl font-bold">{{ productsCount }}</p>
                    </div>
                </div>
                <h2 class="text-xl font-bold mb-4">Pedidos recientes</h2>
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
                                <td class="px-6 py-4">{{ formatPrice(o.total) }}</td>
                                <td class="px-6 py-4">{{ formatPrice(o.advance_payment) }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full" :class="o.status === 'pagado' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700'">{{ o.status }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <button @click="view(o)" class="text-blue-600 hover:text-blue-800" title="Ver">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="!orders.length">
                                <td colspan="7" class="px-6 py-4 text-center text-gray-400 italic">Sin pedidos</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <Modal :show="showDetail" @close="showDetail = false">
        <div class="p-0">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Orden de pedido</h3>
                    <p class="text-sm text-gray-600">{{ detail?.client_name }} · {{ detail?.client_phone }}</p>
                </div>
                <button @click="showDetail = false" class="text-gray-400 hover:text-gray-700 text-2xl leading-none">&times;</button>
            </div>
            <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">
                <div class="flex gap-4">
                    <div v-if="detail?.images?.length" class="w-1/2 flex-shrink-0">
                        <img :src="detail.images[0]" class="w-full aspect-square object-cover rounded-lg border border-gray-200" />
                    </div>
                    <div class="flex-1 grid grid-cols-2 gap-3 text-sm">
                        <div class="bg-gray-50 rounded-lg p-3"><p class="text-xs text-gray-500 uppercase">Total</p><p class="font-bold text-gray-900">{{ formatPrice(detail?.total) }}</p></div>
                        <div class="bg-gray-50 rounded-lg p-3"><p class="text-xs text-gray-500 uppercase">Abono</p><p class="font-bold text-green-600">{{ formatPrice(detail?.advance_payment) }}</p></div>
                        <div class="bg-red-50 rounded-lg p-3 col-span-2"><p class="text-xs text-red-500 uppercase">Saldo pendiente</p><p class="font-bold text-red-600">{{ formatPrice(pendingBalance) }}</p></div>
                        <div class="bg-gray-50 rounded-lg p-3"><p class="text-xs text-gray-500 uppercase">Tela camisa</p><p class="font-medium">{{ detail?.shirt_fabric || '-' }}</p></div>
                        <div class="bg-gray-50 rounded-lg p-3"><p class="text-xs text-gray-500 uppercase">Tela pantaloneta</p><p class="font-medium">{{ detail?.short_fabric || '-' }}</p></div>
                        <div class="bg-gray-50 rounded-lg p-3"><p class="text-xs text-gray-500 uppercase">Calidad media</p><p class="font-medium">{{ detail?.sock_quality || '-' }}</p></div>
                        <div class="bg-gray-50 rounded-lg p-3"><p class="text-xs text-gray-500 uppercase">Tipo cuello</p><p class="font-medium">{{ detail?.collar_type || '-' }}</p></div>
                        <div class="bg-gray-50 rounded-lg p-3 flex items-center gap-2"><input type="checkbox" :checked="detail?.delivered" @change="toggleDetailDelivered" class="w-4 h-4 accent-black" /><p class="text-xs text-gray-500 uppercase">Entregado</p></div>
                    </div>
                </div>
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50"><tr><th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th><th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Dorsal</th><th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Talla camisa</th><th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Talla pantaloneta</th><th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th></tr></thead>
                        <tbody class="divide-y divide-gray-100"><tr v-for="(pl,i) in (detail?.players||[])" :key="i"><td class="px-3 py-2 font-medium">{{ pl.name }}</td><td class="px-3 py-2">{{ pl.number }}</td><td class="px-3 py-2">{{ pl.size }}</td><td class="px-3 py-2">{{ pl.short_size }}</td><td class="px-3 py-2">{{ pl.type }}</td></tr></tbody>
                    </table>
                </div>
                <div v-if="detail?.notes" class="bg-amber-50 border border-amber-100 rounded-lg p-3 text-sm text-gray-700">{{ detail.notes }}</div>
            </div>
        </div>
    </Modal>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import AuthenticatedLayout from '../Layouts/AuthenticatedLayout.vue';
import Modal from '../Components/Modal.vue';
import axios from 'axios';
const orders = ref([]);
const productsCount = ref(0);
const loading = ref(true);
const showDetail = ref(false);
const detail = ref(null);
const view = (o) => { detail.value = JSON.parse(JSON.stringify(o)); showDetail.value = true; };
const toggleDetailDelivered = async () => {
    if (!detail.value) return;
    try {
        await axios.put(`/api/orders/${detail.value.id}`, { delivered: !detail.value.delivered });
        detail.value.delivered = !detail.value.delivered;
        await load();
    } catch (e) {}
};
const load = async () => { loading.value = true; try { const { data } = await axios.get('/api/orders'); orders.value = data.data ?? data; const p = await axios.get('/api/products'); productsCount.value = (p.data.data ?? p.data).length; } finally { loading.value = false; } };
onMounted(load);
const pending = computed(() => orders.value.filter(o => o.status !== 'pagado').length);
const pendingBalance = computed(() => (Number(detail.value?.total) || 0) - (Number(detail.value?.advance_payment) || 0));
const earnings = computed(() => orders.value.reduce((s, o) => s + (Number(o.advance_payment) || 0), 0));
const formatPrice = (v) => {
    if (v === null || v === undefined || v === '') return '';
    const n = Number(v);
    if (isNaN(n)) return v;
    return '$' + Math.round(n).toLocaleString('es-CO');
};
</script>