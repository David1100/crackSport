<template>
  <Transition name="modal" appear>
    <div v-if="show" class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50">
      <Transition name="fade" appear>
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="$emit('close')" />
      </Transition>
      <div class="mb-6 bg-white rounded-2xl overflow-hidden shadow-2xl transform transition-all sm:w-full sm:mx-auto" :class="maxWidthClass">
        <slot />
      </div>
    </div>
  </Transition>
</template>
<script setup>
const props = defineProps({ show: Boolean, maxWidth: { type: String, default: '2xl' } })
const map = { sm: 'sm:max-w-sm', md: 'sm:max-w-md', lg: 'sm:max-w-lg', xl: 'sm:max-w-xl', '2xl': 'sm:max-w-2xl' }
const maxWidthClass = map[props.maxWidth] || 'sm:max-w-2xl'
defineEmits(['close'])
</script>
<style scoped>
.modal-enter-active, .modal-leave-active { transition: opacity .25s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
.modal-enter-active .mb-6, .modal-leave-active .mb-6 { transition: transform .25s ease, opacity .25s ease; }
.modal-enter-from .mb-6 { transform: translateY(12px) scale(.98); opacity: 0; }
.modal-leave-to .mb-6 { transform: translateY(12px) scale(.98); opacity: 0; }
.fade-enter-active, .fade-leave-active { transition: opacity .25s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>


