<template>
    <div class="min-h-screen flex">
        <button type="button" class="fixed bottom-4 right-4 z-40 md:hidden bg-black text-white p-3 rounded-full shadow-lg" @click="sidebarOpen = !sidebarOpen">☰</button>
        <aside class="fixed md:static inset-y-0 left-0 z-30 w-64 bg-white border-r border-gray-200 flex-shrink-0 transform transition-transform duration-200" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'">
            <div class="p-6">
                <img src="/logo_principal.webp" alt="CRS Sport" class="h-10 object-contain" />
            </div>
            <nav class="mt-4 space-y-1 px-3">
                <Link href="/dashboard" class="flex items-center gap-3 px-4 py-2 rounded" :class="isActive('/dashboard') ? 'bg-black text-white' : 'text-black hover:bg-gray-100'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h3m10-11v10a1 1 0 001 1h3m-10 0h4" /></svg>
                    <span>Dashboard</span>
                </Link>
                <Link href="/categories" class="flex items-center gap-3 px-4 py-2 rounded" :class="isActive('/categories') ? 'bg-black text-white' : 'text-black hover:bg-gray-100'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    <span>Categorías</span>
                </Link>
                <Link href="/products" class="flex items-center gap-3 px-4 py-2 rounded" :class="isActive('/products') ? 'bg-black text-white' : 'text-black hover:bg-gray-100'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    <span>Productos</span>
                </Link>
                <Link href="/orders" class="flex items-center gap-3 px-4 py-2 rounded" :class="isActive('/orders') ? 'bg-black text-white' : 'text-black hover:bg-gray-100'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                    <span>Pedidos</span>
                </Link>

                <!-- Configuraciones (submenu) -->
                <div>
                    <button type="button" class="w-full flex items-center gap-3 px-4 py-2 rounded text-black hover:bg-gray-100" :class="configOpen ? 'bg-gray-100' : ''" @click="configOpen = !configOpen">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        <span>Configuraciones</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-auto transition-transform" :class="configOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div v-show="configOpen" class="ml-4 mt-1 space-y-1">
                        <Link href="/users" class="flex items-center gap-3 px-4 py-2 rounded text-sm" :class="isActive('/users') ? 'bg-black text-white' : 'text-black hover:bg-gray-100'">
                            <span>Usuarios</span>
                        </Link>
                        <Link href="/clients" class="flex items-center gap-3 px-4 py-2 rounded text-sm" :class="isActive('/clients') ? 'bg-black text-white' : 'text-black hover:bg-gray-100'">
                            <span>Clientes</span>
                        </Link>
                        <Link href="/designs" class="flex items-center gap-3 px-4 py-2 rounded text-sm" :class="isActive('/designs') ? 'bg-black text-white' : 'text-black hover:bg-gray-100'">
                            <span>Diseños</span>
                        </Link>
                        <Link href="/combos" class="flex items-center gap-3 px-4 py-2 rounded text-sm" :class="isActive('/combos') ? 'bg-black text-white' : 'text-black hover:bg-gray-100'">
                            <span>Combos</span>
                        </Link>
                        <Link href="/telas" class="flex items-center gap-3 px-4 py-2 rounded text-sm" :class="isActive('/telas') ? 'bg-black text-white' : 'text-black hover:bg-gray-100'">
                            <span>Telas</span>
                        </Link>
                    </div>
                </div>

            </nav>
        </aside>
        <div class="flex-1 flex flex-col">
            <header class="bg-white shadow-sm">
                <div class="px-6 py-4 flex justify-between items-center">
                    <h1 class="text-lg font-bold">{{ title }}</h1>
                    <button type="button" @click="logout" class="text-sm text-gray-600 hover:text-black">Cerrar sesion</button>
                </div>
            </header>
            <main class="flex-1 p-6">
                <slot />
            </main>
        </div>
    </div>
</template>

<script setup>
import { Link, usePage, router } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

const page = usePage();
const title = computed(() => page.props.title || 'Panel');
const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
const logout = () => router.post('/logout');
const isActive = (path) => page.url.startsWith(path);

const configOpen = ref(false);
const sidebarOpen = ref(false);
const configPaths = ['/users', '/clients', '/designs', '/combos', '/telas'];
watch(() => page.url, (url) => {
    if (configPaths.some(p => url.startsWith(p))) configOpen.value = true;
}, { immediate: true });
</script>