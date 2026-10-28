<template>
  <input :type="type" :class="['border-gray-300 focus:border-gray-500 focus:ring-gray-500 rounded-md shadow-sm', klass]" v-model="model" />
</template>
<script setup>
import { computed } from 'vue'
const props = defineProps({ type: { type: String, default: 'text' }, class: String, modelValue: [String, Number] })
const emit = defineEmits(['update:modelValue'])
const model = computed({ get: () => props.modelValue, set: (v) => emit('update:modelValue', v) })
const klass = props.class
</script>



