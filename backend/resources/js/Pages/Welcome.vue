<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="text-center">
            <h1 class="text-4xl font-bold">Welcome to CRS Sport</h1>
            <p class="mt-4 text-gray-600">Your sportswear management platform.</p>
            <div class="mt-6 space-x-4">
                <Link href="/login" class="px-4 py-2 bg-black text-white rounded">Login</Link>
                <Link href="/register" class="px-4 py-2 border rounded">Register</Link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>