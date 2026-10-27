<template>
  <div class="relative">
    <div @click="open = !open"><slot name="trigger" /></div>
    <div v-show="open" class="absolute z-50 mt-2 w-48 rounded-md shadow-lg ltr:origin-top-right rtl:origin-top-left end-0" @click="open = false">
      <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white"><slot name="content" /></div>
    </div>
  </div>
</template>
<script setup>
import { ref } from 'vue'
const open = ref(false)
</script>


