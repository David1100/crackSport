<template>
  <label class="block font-medium text-sm text-gray-700">
    {{ value ?? '' }}<slot />
  </label>
</template>
<script setup>
defineProps({ value: String })
</script>


