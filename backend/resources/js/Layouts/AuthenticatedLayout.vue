<template>
    <div class="min-h-screen flex">
        <aside class="w-64 bg-white border-r border-gray-200 flex-shrink-0">
            <div class="p-6">
                <img src="/logo_principal.webp" alt="CRS Sport" class="h-10 object-contain" />
            </div>
            <nav class="mt-4 space-y-1 px-3">
                <Link href="/dashboard" class="flex items-center gap-3 px-4 py-2 rounded" :class="isActive('/dashboard') ? 'bg-black text-white' : 'text-black hover:bg-gray-100'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h3m10-11v10a1 1 0 001 1h3m-10 0h4" /></svg>
                    <span>Dashboard</span>
                </Link>
                <Link href="/users" class="flex items-center gap-3 px-4 py-2 rounded" :class="isActive('/users') ? 'bg-black text-white' : 'text-black hover:bg-gray-100'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87m8-6a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    <span>Usuarios</span>
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
                <Link href="/clients" class="flex items-center gap-3 px-4 py-2 rounded" :class="isActive('/clients') ? 'bg-black text-white' : 'text-black hover:bg-gray-100'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87m8-6a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    <span>Clientes</span>
                </Link>
                <Link href="/designs" class="flex items-center gap-3 px-4 py-2 rounded" :class="isActive('/designs') ? 'bg-black text-white' : 'text-black hover:bg-gray-100'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14" /></svg>
                    <span>Diseños</span>
                </Link>
                <Link href="/combos" class="flex items-center gap-3 px-4 py-2 rounded" :class="isActive('/combos') ? 'bg-black text-white' : 'text-black hover:bg-gray-100'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    <span>Combos</span>
                </Link>
                <Link href="/telas" class="flex items-center gap-3 px-4 py-2 rounded" :class="isActive('/telas') ? 'bg-black text-white' : 'text-black hover:bg-gray-100'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4h16v4H4zM4 8v12h16V8" /></svg>
                    <span>Telas</span>
                </Link>
                <Link href="/profile" class="flex items-center gap-3 px-4 py-2 rounded" :class="isActive('/profile') ? 'bg-black text-white' : 'text-black hover:bg-gray-100'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM4 20a8 8 0 0116 0" /></svg>
                    <span>Perfil</span>
                </Link>
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
import { computed } from 'vue';

const page = usePage();
const title = computed(() => page.props.title || 'Panel');
const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
const logout = () => router.post('/logout');
const isActive = (path) => page.url.startsWith(path);
</script>