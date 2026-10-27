<template>
    <AuthenticatedLayout title="Pedidos">
        <div class="py-6">
            <div class="max-w-7xl mx-auto">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold">Pedidos</h2>
                    <div class="flex items-center gap-2">
                        <select v-model="filterStatus" class="border rounded px-2 py-1 text-sm">
                            <option value="">Todos los estados</option>
                            <option value="pagado">Pagado</option>
                            <option value="pendiente">Pendiente</option>
                        </select>
                        <select v-model="filterDelivered" class="border rounded px-2 py-1 text-sm">
                            <option value="">Entrega (todos)</option>
                            <option value="si">Entregados</option>
                            <option value="no">No entregados</option>
                        </select>
                        <button @click="openCreate" class="px-4 py-2 bg-black text-white rounded-md text-sm font-medium hover:bg-gray-800">Nuevo</button>
                    </div>
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Entregado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="o in filteredOrders" :key="o.id">
                                <td class="px-6 py-4">{{ o.client_name }}</td>
                                <td class="px-6 py-4">{{ o.client_phone }}</td>
                                <td class="px-6 py-4">{{ (o.players || []).length }}</td>
                                <td class="px-6 py-4">{{ formatPrice(o.total) }}</td>
                                <td class="px-6 py-4">{{ formatPrice(o.advance_payment) }}</td>
                                <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-medium rounded-full" :class="o.status === 'pagado' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700'">{{ o.status }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <input type="checkbox" :checked="o.delivered" @change="toggleDelivered(o)" class="w-4 h-4 accent-black" />
                            </td>
                                <td class="px-6 py-4 flex gap-2">
                                    <button @click="view(o)" class="text-blue-600 hover:text-blue-800" title="Ver">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </button>
                                    <button @click="edit(o)" class="text-amber-600 hover:text-amber-800" title="Editar">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </button>
                                    <button @click="abonar(o)" class="text-green-600 hover:text-green-800" title="Abonar">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </button>
                                    <button @click="remove(o.id)" class="text-red-600 hover:text-red-800" title="Eliminar">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <Modal :show="showModal" @close="showModal = false">
            <div class="p-0">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">{{ editingId ? 'Editar Pedido' : 'Nuevo Pedido' }}</h3>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-700 text-2xl leading-none">&times;</button>
                </div>
                <form @submit.prevent="submit" class="p-6 space-y-5 max-h-[75vh] overflow-y-auto">
                    <div>
                        <InputLabel value="Tipo de pedido" />
                        <div class="mt-1 flex gap-3">
                            <label class="flex items-center gap-2"><input type="radio" value="team" v-model="form.order_type" /> Uniformes (equipo)</label>
                            <label class="flex items-center gap-2"><input type="radio" value="individual" v-model="form.order_type" /> Producto individual</label>
                        </div>
                    </div>
                    <div v-if="form.order_type === 'individual'" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <InputLabel value="Producto" />
                            <select v-model="form.product_id" @change="onProductChange" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-black focus:ring-black">
                                <option value="">Seleccionar</option>
                                <option v-for="p in products" :key="p.id" :value="p.id">{{ p.name }}</option>
                            </select>
                        </div>
                        <div>
                            <InputLabel value="Cantidad" />
                            <TextInput v-model="form.quantity" type="number" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-black focus:ring-black" />
                        </div>
                        <div>
                            <InputLabel value="Talla" />
                            <TextInput v-model="form.size" type="text" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-black focus:ring-black uppercase" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <InputLabel value="Nombre del cliente" />
                            <TextInput v-model="form.client_name" type="text" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-black focus:ring-black" required />
                        </div>
                        <div>
                            <InputLabel value="Teléfono" />
                            <TextInput v-model="form.client_phone" type="text" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-black focus:ring-black" />
                        </div>
                    </div>
                    <div v-if="form.order_type === 'team'" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <InputLabel value="Tipo de tela camisa" />
                            <select v-model="form.shirt_fabric" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-black focus:ring-black">
                                <option value="">Seleccionar</option>
                                <option v-for="t in telas" :key="t.id" :value="t.name">{{ t.name }}</option>
                            </select>
                        </div>
                        <div>
                            <InputLabel value="Tipo de tela pantaloneta" />
                            <select v-model="form.short_fabric" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-black focus:ring-black">
                                <option value="">Seleccionar</option>
                                <option v-for="t in telas" :key="t.id" :value="t.name">{{ t.name }}</option>
                            </select>
                        </div>
                        <div>
                            <InputLabel value="Calidad de media" />
                            <select v-model="form.sock_quality" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-black focus:ring-black">
                                <option value="">Seleccionar</option>
                                <option value="Estandar">Estandar</option>
                                <option value="Profesional">Profesional</option>
                            </select>
                        </div>
                        <div>
                            <InputLabel value="Tipo de cuello" />
                            <TextInput v-model="form.collar_type" type="text" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-black focus:ring-black" />
                        </div>
                    </div>
                    <div>
                        <InputLabel value="Diseños (imágenes)" />
                        <label class="mt-1 flex items-center justify-center w-full h-24 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-black hover:bg-gray-50 transition">
                            <span class="text-sm text-gray-500">Click para seleccionar imágenes</span>
                            <input type="file" multiple accept="image/*" @change="onImages" class="hidden" />
                        </label>
                        <p v-if="images.length" class="mt-1 text-xs text-gray-500">{{ images.length }} archivo(s) seleccionado(s)</p>
                        <div v-if="existingImages.length" class="mt-2 grid grid-cols-4 gap-2">
                            <div v-for="(im,i) in existingImages" :key="i" class="relative">
                                <img :src="im" class="w-full aspect-square object-cover rounded-lg border border-gray-200" />
                                <button type="button" @click="existingImages.splice(i,1)" class="absolute top-1 right-1 bg-red-600 text-white rounded-full w-5 h-5 text-xs flex items-center justify-center">&times;</button>
                            </div>
                        </div>
                    </div>
                    <div v-if="form.order_type === 'team'">
                        <div class="flex items-center justify-between mb-2">
                            <InputLabel value="Jugadores" />
                            <button type="button" @click="addPlayer" class="text-sm font-medium text-black hover:underline">+ Agregar</button>
                        </div>
                        <div v-for="(pl, idx) in players" :key="idx" class="grid grid-cols-12 gap-2 mb-2 p-3 bg-gray-50 rounded-lg">
                            <TextInput v-model="pl.name" placeholder="Nombre" class="col-span-2 rounded-lg border-gray-300 uppercase" />
                            <TextInput v-model="pl.number" placeholder="Dorsal" class="col-span-1 rounded-lg border-gray-300" />
                            <TextInput v-model="pl.size" placeholder="Talla camisa" class="col-span-1 rounded-lg border-gray-300 uppercase" />
                            <TextInput v-model="pl.short_size" placeholder="Talla pantaloneta" class="col-span-1 rounded-lg border-gray-300 uppercase" />
                            <select v-model="pl.type" class="col-span-2 rounded-lg border-gray-300 text-sm">
                                <option value="jugador">Jugador</option>
                                <option value="arquero">Arquero</option>
                            </select>
                            <TextInput v-model="pl.details" placeholder="Detalles" class="col-span-4 rounded-lg border-gray-300" />
                            <button type="button" @click="players.splice(idx,1)" class="col-span-1 text-red-500 hover:text-red-700 flex items-center justify-center" title="Eliminar">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </div>
                        <p v-if="!players.length" class="text-xs text-gray-400 italic">Sin jugadores agregados.</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <InputLabel value="Total" />
                            <TextInput v-model="form.total" type="number" step="0.01" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-black focus:ring-black" />
                        </div>
                        <div>
                            <InputLabel value="Abono" />
                            <TextInput v-model="form.advance_payment" type="number" step="0.01" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-black focus:ring-black" />
                        </div>
                    </div>
                    <div>
                        <InputLabel value="Notas" />
                        <textarea v-model="form.notes" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-black focus:ring-black" placeholder="Observaciones del pedido..."></textarea>
                    </div>
                    <div class="flex justify-end gap-2 pt-2 border-t border-gray-100">
                        <SecondaryButton type="button" @click="showModal = false">Cancelar</SecondaryButton>
                        <PrimaryButton type="submit">Guardar</PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>
        <Modal :show="showDetail" @close="showDetail = false">
            <div class="p-0">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Orden de pedido</h3>
                        <p class="text-sm text-gray-700 font-medium">{{ detail?.client_name }}</p>
                        <p class="text-sm text-gray-500">{{ detail?.client_phone }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button @click="exportPdf" class="px-3 py-1.5 text-sm bg-black text-white rounded-md hover:bg-gray-800">Exportar PDF</button>
                        <button @click="showDetail = false" class="text-gray-400 hover:text-gray-700 text-2xl leading-none">&times;</button>
                    </div>
                </div>
                <div class="p-6 space-y-5 max-h-[75vh] overflow-y-auto">
                    <div v-if="detail?.order_type === 'individual'" class="bg-indigo-50 border border-indigo-100 rounded-lg p-3 text-sm">
                        <p><strong>Producto:</strong> {{ detail?.product_name }}</p>
                        <p><strong>Cantidad:</strong> {{ detail?.quantity }} &nbsp; <strong>Talla:</strong> {{ detail?.size }}</p>
                    </div>
                    <div class="flex gap-4">
                        <div v-if="detail?.images?.length" class="w-1/2 flex-shrink-0">
                            <p class="text-sm font-medium text-gray-700 mb-2">Diseños</p>
                            <div class="relative">
                                <img :src="(detail.images[detailImageIndex] || '').startsWith('http') ? detail.images[detailImageIndex] : detail.images[detailImageIndex]" class="w-full aspect-square object-cover rounded-lg border border-gray-200" />
                                <button v-if="detail.images.length > 1" type="button" @click="prevDetailImage" class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/50 text-white rounded-full w-8 h-8 flex items-center justify-center">&lt;</button>
                                <button v-if="detail.images.length > 1" type="button" @click="nextDetailImage" class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/50 text-white rounded-full w-8 h-8 flex items-center justify-center">&gt;</button>
                            </div>
                            <p v-if="detail.images.length > 1" class="text-xs text-gray-500 text-center mt-1">{{ detailImageIndex + 1 }} / {{ detail.images.length }}</p>
                        </div>
                        <div class="flex-1 space-y-3">
                            <div class="grid grid-cols-2 gap-3">
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <p class="text-xs text-gray-500 uppercase">Total</p>
                                    <p class="text-lg font-bold text-gray-900">{{ formatPrice(detail?.total) }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <p class="text-xs text-gray-500 uppercase">Abono</p>
                                    <p class="text-lg font-bold text-green-600">{{ formatPrice(detail?.advance_payment) }}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <p class="text-xs text-gray-500 uppercase">Tela camisa</p>
                                    <p class="font-medium text-gray-900">{{ detail?.shirt_fabric || '-' }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <p class="text-xs text-gray-500 uppercase">Tela pantaloneta</p>
                                    <p class="font-medium text-gray-900">{{ detail?.short_fabric || '-' }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <p class="text-xs text-gray-500 uppercase">Calidad media</p>
                                    <p class="font-medium text-gray-900">{{ detail?.sock_quality || '-' }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <p class="text-xs text-gray-500 uppercase">Tipo cuello</p>
                                    <p class="font-medium text-gray-900">{{ detail?.collar_type || '-' }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-3 flex items-center gap-2">
                                    <input type="checkbox" :checked="detail?.delivered" @change="toggleDetailDelivered" class="w-4 h-4 accent-black" />
                                    <p class="text-xs text-gray-500 uppercase">Entregado</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-700 mb-2">Jugadores ({{ (detail?.players || []).length }})</p>
                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                            <table class="min-w-full text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Dorsal</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Talla camisa</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Talla pantaloneta</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Detalles</th>
                                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">Listo</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <tr v-for="(pl,i) in (detail?.players || [])" :key="i" class="hover:bg-gray-50">
                                        <td class="px-3 py-2 font-medium">{{ pl.name }}</td>
                                        <td class="px-3 py-2">{{ pl.number }}</td>
                                        <td class="px-3 py-2">{{ pl.size }}</td>
                                        <td class="px-3 py-2">{{ pl.short_size }}</td>
                                        <td class="px-3 py-2">
                                            <span class="px-2 py-0.5 text-xs rounded-full" :class="pl.type === 'arquero' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700'">{{ pl.type }}</span>
                                        </td>
                                        <td class="px-3 py-2 text-gray-600">{{ pl.details }}</td>
                                        <td class="px-3 py-2 text-center">
                                            <input type="checkbox" :checked="pl.ready" @change="toggleReady(pl)" class="w-4 h-4 accent-black" />
                                        </td>
                                    </tr>
                                    <tr v-if="!(detail?.players || []).length">
                                        <td colspan="7" class="px-3 py-4 text-center text-gray-400 italic">Sin jugadores</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div v-if="detail?.notes" class="bg-amber-50 border border-amber-100 rounded-lg p-3">
                        <p class="text-xs text-amber-700 uppercase mb-1">Notas</p>
                        <p class="text-sm text-gray-700">{{ detail.notes }}</p>
                    </div>
                </div>
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
import axios from 'axios';
import Swal from 'sweetalert2';
const orders = ref([]);
const telas = ref([]);
const products = ref([]);
const filterStatus = ref('');
const filterDelivered = ref('');
const filteredOrders = computed(() => orders.value.filter(o => {
    if (filterStatus.value && o.status !== filterStatus.value) return false;
    if (filterDelivered.value === 'si' && !o.delivered) return false;
    if (filterDelivered.value === 'no' && o.delivered) return false;
    return true;
}));
const loading = ref(true);
const showModal = ref(false);
const editingId = ref(null);
const showDetail = ref(false);
const detailImageIndex = ref(0);
const detail = ref(null);
const errors = ref({});
const form = ref({ client_name: '', client_phone: '', notes: '', advance_payment: '', total: '', shirt_fabric: '', short_fabric: '', sock_quality: '', collar_type: '', order_type: 'team', product_id: '', product_name: '', quantity: '', size: '' });
const images = ref([]);
const existingImages = ref([]);
const players = ref([]);
const load = async () => { loading.value = true; try { const { data } = await axios.get('/api/orders'); orders.value = data.data ?? data; } finally { loading.value = false; } };
const loadTelas = async () => { try { const { data } = await axios.get('/api/telas'); telas.value = data.data ?? data; } catch (e) {} };
const loadProducts = async () => { try { const { data } = await axios.get('/api/products'); products.value = data.data ?? data; } catch (e) {} };
onMounted(() => { load(); loadTelas(); loadProducts(); });
const openCreate = () => { editingId.value = null; showModal.value = true; form.value = { client_name: '', client_phone: '', notes: '', advance_payment: '', total: '', shirt_fabric: '', short_fabric: '', sock_quality: '', collar_type: '', order_type: 'team', product_id: '', product_name: '', quantity: '', size: '' }; images.value = []; existingImages.value = []; players.value = []; errors.value = {}; };
const onImages = (e) => { images.value = Array.from(e.target.files || []); };
const addPlayer = () => { players.value.push({ name: '', number: '', size: '', short_size: '', type: 'jugador', details: '', ready: false }); };
const onProductChange = () => {
    const p = products.value.find(x => x.id == form.value.product_id);
    if (p) { form.value.product_name = p.name; if (p.price) form.value.total = p.price; }
};
const submit = async () => {
    errors.value = {};
    const fd = new FormData();
    fd.append('client_name', form.value.client_name);
    if (form.value.client_phone) fd.append('client_phone', form.value.client_phone);
    if (form.value.notes) fd.append('notes', form.value.notes);
    if (form.value.advance_payment !== '') fd.append('advance_payment', form.value.advance_payment);
    if (form.value.total !== '') fd.append('total', form.value.total);
    if (form.value.shirt_fabric) fd.append('shirt_fabric', form.value.shirt_fabric);
    if (form.value.short_fabric) fd.append('short_fabric', form.value.short_fabric);
    if (form.value.sock_quality) fd.append('sock_quality', form.value.sock_quality);
    if (form.value.collar_type) fd.append('collar_type', form.value.collar_type);
    fd.append('order_type', form.value.order_type || 'team');
    if (form.value.product_id) fd.append('product_id', form.value.product_id);
    if (form.value.product_name) fd.append('product_name', form.value.product_name);
    if (form.value.quantity) fd.append('quantity', form.value.quantity);
    if (form.value.size) fd.append('size', form.value.size);
    images.value.forEach((f) => fd.append('images[]', f));
    existingImages.value.forEach((u) => fd.append('keep_images[]', u));
    players.value.forEach((pl, i) => {
        fd.append(`players[${i}][name]`, (pl.name || '').toUpperCase());
        fd.append(`players[${i}][number]`, pl.number);
        fd.append(`players[${i}][size]`, (pl.size || '').toUpperCase());
        fd.append(`players[${i}][short_size]`, (pl.short_size || '').toUpperCase());
        fd.append(`players[${i}][type]`, pl.type);
        fd.append(`players[${i}][details]`, pl.details);
        fd.append(`players[${i}][ready]`, pl.ready ? '1' : '0');
    });
    try {
        if (editingId.value) {
            fd.append('_method', 'PUT');
            await axios.post(`/api/orders/${editingId.value}`, fd, { headers: { 'Content-Type': 'multipart/form-data' } });
        } else {
            await axios.post('/api/orders', fd, { headers: { 'Content-Type': 'multipart/form-data' } });
        }
        showModal.value = false;
        editingId.value = null;
        await load();
        Swal.fire({ icon: 'success', title: 'Guardado', timer: 1500, showConfirmButton: false });
    } catch (e) {
        errors.value = e.response?.data?.errors || {};
    }
};
const view = (o) => { detail.value = JSON.parse(JSON.stringify(o)); detailImageIndex.value = 0; showDetail.value = true; };
const exportPdf = () => {
    const d = detail.value;
    if (!d) return;
    const playersRows = (d.players || []).map(p => `<tr><td>${p.name||''}</td><td>${p.number||''}</td><td>${p.size||''}</td><td>${p.short_size||''}</td><td>${p.type||''}</td><td>${p.details||''}</td><td>${p.ready?'Listo':'Pendiente'}</td></tr>`).join('');
    const imagesHtml = (d.images || []).map(im => `<img src="${im}" style="width:120px;height:120px;object-fit:cover;margin:4px;border:1px solid #ddd;" />`).join('');
    const html = `<!DOCTYPE html><html><head><title>Orden de pedido</title><style>body{font-family:Arial,sans-serif;padding:20px;}h1{font-size:20px;}table{width:100%;border-collapse:collapse;margin-top:10px;}th,td{border:1px solid #ddd;padding:6px;font-size:12px;}th{background:#f5f5f5;} .grid{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin:10px 0;} .card{background:#f9f9f9;padding:8px;border-radius:6px;}</style></head><body>
    <h1>Orden de pedido</h1>
    <p><strong>Cliente:</strong> ${d.client_name||''} &nbsp; <strong>Teléfono:</strong> ${d.client_phone||''}</p>
    <div class="grid">
        <div class="card"><strong>Total:</strong> ${formatPrice(d.total)}</div>
        <div class="card"><strong>Abono:</strong> ${formatPrice(d.advance_payment)}</div>
        <div class="card"><strong>Tela camisa:</strong> ${d.shirt_fabric||'-'}</div>
        <div class="card"><strong>Tela pantaloneta:</strong> ${d.short_fabric||'-'}</div>
        <div class="card"><strong>Calidad media:</strong> ${d.sock_quality||'-'}</div>
        <div class="card"><strong>Tipo cuello:</strong> ${d.collar_type||'-'}</div>
    </div>
    <div>${imagesHtml}</div>
    <h3>Jugadores</h3>
    <table><thead><tr><th>Nombre</th><th>Dorsal</th><th>Talla camisa</th><th>Talla pantaloneta</th><th>Tipo</th><th>Detalles</th><th>Estado</th></tr></thead><tbody>${playersRows}</tbody></table>
    ${d.notes ? `<p><strong>Notas:</strong> ${d.notes}</p>` : ''}
    </body></html>`;
    const w = window.open('', '_blank');
    w.document.write(html);
    w.document.close();
    w.focus();
    setTimeout(() => { w.print(); }, 500);
};
const prevDetailImage = () => { if (!detail.value?.images?.length) return; detailImageIndex.value = (detailImageIndex.value - 1 + detail.value.images.length) % detail.value.images.length; };
const nextDetailImage = () => { if (!detail.value?.images?.length) return; detailImageIndex.value = (detailImageIndex.value + 1) % detail.value.images.length; };
const toggleReady = async (pl) => {
    pl.ready = !pl.ready;
    const d = detail.value;
    const fd = new FormData();
    fd.append('client_name', d.client_name || '');
    if (d.client_phone) fd.append('client_phone', d.client_phone);
    if (d.notes) fd.append('notes', d.notes);
    if (d.advance_payment !== '' && d.advance_payment !== null) fd.append('advance_payment', d.advance_payment);
    if (d.total !== '' && d.total !== null) fd.append('total', d.total);
    if (d.shirt_fabric) fd.append('shirt_fabric', d.shirt_fabric);
    if (d.short_fabric) fd.append('short_fabric', d.short_fabric);
    if (d.sock_quality) fd.append('sock_quality', d.sock_quality);
    if (d.collar_type) fd.append('collar_type', d.collar_type);
    fd.append('_method', 'PUT');
    (d.images || []).forEach((u) => fd.append('keep_images[]', u));
    (d.players || []).forEach((p, i) => {
        fd.append(`players[${i}][name]`, (p.name || '').toUpperCase());
        fd.append(`players[${i}][number]`, p.number || '');
        fd.append(`players[${i}][size]`, (p.size || '').toUpperCase());
        fd.append(`players[${i}][short_size]`, (p.short_size || '').toUpperCase());
        fd.append(`players[${i}][type]`, p.type || 'jugador');
        fd.append(`players[${i}][details]`, p.details || '');
        fd.append(`players[${i}][ready]`, p.ready ? '1' : '0');
    });
    try {
        const { data } = await axios.post(`/api/orders/${d.id}`, fd, { headers: { 'Content-Type': 'multipart/form-data' } });
        detail.value = data;
        await load();
    } catch (e) {}
};
const edit = (o) => {
    editingId.value = o.id;
    showModal.value = true;
    form.value = {
        client_name: o.client_name || '',
        client_phone: o.client_phone || '',
        notes: o.notes || '',
        advance_payment: o.advance_payment || '',
        total: o.total || '',
        shirt_fabric: o.shirt_fabric || '',
        short_fabric: o.short_fabric || '',
        sock_quality: o.sock_quality || '',
        collar_type: o.collar_type || '',
        order_type: o.order_type || 'team',
        product_id: o.product_id || '',
        product_name: o.product_name || '',
        quantity: o.quantity || '',
        size: o.size || ''
    };
    images.value = [];
    existingImages.value = [...(o.images || [])];
    players.value = (o.players || []).map(p => ({ name: p.name || '', number: p.number || '', size: p.size || '', short_size: p.short_size || '', type: p.type || 'jugador', details: p.details || '', ready: !!p.ready }));
    errors.value = {};
};
const abonar = async (o) => {
    const { value } = await Swal.fire({ title: 'Abono', input: 'number', inputLabel: 'Monto', showCancelButton: true });
    if (!value) return;
    await axios.put(`/api/orders/${o.id}`, { advance_payment: value });
    await load();
};
const toggleDetailDelivered = async () => {
    if (!detail.value) return;
    try {
        await axios.put(`/api/orders/${detail.value.id}`, { delivered: !detail.value.delivered });
        detail.value.delivered = !detail.value.delivered;
        await load();
    } catch (e) {}
};
const toggleDelivered = async (o) => {
    try {
        await axios.put(`/api/orders/${o.id}`, { delivered: !o.delivered });
        o.delivered = !o.delivered;
    } catch (e) {}
};
const remove = async (id) => {
    const r = await Swal.fire({ title: 'Eliminar?', icon: 'warning', showCancelButton: true, confirmButtonText: 'Si', cancelButtonText: 'No' });
    if (!r.isConfirmed) return;
    await axios.delete(`/api/orders/${id}`);
    await load();
};
const formatPrice = (v) => {
    if (v === null || v === undefined || v === '') return '';
    const n = Number(v);
    if (isNaN(n)) return v;
    return '$' + Math.round(n).toLocaleString('es-CO');
};
</script>
