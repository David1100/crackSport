<template>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-100 px-4">
        <div class="w-full max-w-md bg-white shadow rounded-lg p-8">
            <slot />
        </div>
        <p class="mt-6 text-sm text-gray-500">
            <Link href="/">CRS Sport</Link>
        </p>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>