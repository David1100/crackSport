<template>
    <div>
        <div
            class="relative flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-xl bg-gray-50 hover:bg-gray-100 hover:border-gray-400 transition cursor-pointer"
            @dragover.prevent="dragging = true"
            @dragleave.prevent="dragging = false"
            @drop.prevent="onDrop"
            @click="trigger"
        >
            <input ref="input" type="file" accept="image/*" class="hidden" @change="onSelect" />
            <img v-if="preview" :src="preview" alt="preview" class="absolute inset-0 w-full h-full object-cover rounded-xl" />
            <div v-else class="text-center text-gray-500">
                <svg class="mx-auto h-8 w-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.9A5 5 0 1115.9 6h.1a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                <p class="text-sm">Arrastra una imagen o haz clic para seleccionar</p>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref } from 'vue';
const props = defineProps({ file: File, preview: String });
const emit = defineEmits(['update:file', 'update:preview']);
const dragging = ref(false);
const input = ref(null);
const trigger = () => input.value?.click();
const setFile = (f) => {
    emit('update:file', f);
    if (f) {
        const reader = new FileReader();
        reader.onload = (e) => emit('update:preview', e.target.result);
        reader.readAsDataURL(f);
    } else {
        emit('update:preview', null);
    }
};
const onSelect = (e) => { const f = e.target.files?.[0]; if (f) setFile(f); };
const onDrop = (e) => { dragging.value = false; const f = e.dataTransfer?.files?.[0]; if (f && f.type.startsWith('image/')) setFile(f); };
</script>


