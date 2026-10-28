<template>
  <ul v-if="message" class="text-sm text-red-600 space-y-1">
    <li v-for="(m, i) in (Array.isArray(message) ? message : [message])" :key="i">{{ m }}</li>
  </ul>
</template>
<script setup>
defineProps({ message: [String, Array] })
</script>



