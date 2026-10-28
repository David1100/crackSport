<template>
    <div class="border border-gray-300 rounded-md">
        <div class="flex flex-wrap gap-2 p-2 border-b border-gray-200 bg-gray-50">
            <button type="button" @click="editor.chain().focus().toggleHeading({ level: 1 }).run()" :class="btnClass(editor.isActive('heading', { level: 1 }))">H1</button>
            <button type="button" @click="editor.chain().focus().toggleHeading({ level: 2 }).run()" :class="btnClass(editor.isActive('heading', { level: 2 }))">H2</button>
            <button type="button" @click="editor.chain().focus().toggleHeading({ level: 3 }).run()" :class="btnClass(editor.isActive('heading', { level: 3 }))">H3</button>
            <button type="button" @click="editor.chain().focus().toggleBold().run()" :class="btnClass(editor.isActive('bold'))">Negrita</button>
            <button type="button" @click="editor.chain().focus().toggleItalic().run()" :class="btnClass(editor.isActive('italic'))">Cursiva</button>
            <button type="button" @click="editor.chain().focus().toggleUnderline().run()" :class="btnClass(editor.isActive('underline'))">Subrayado</button>
            <button type="button" @click="editor.chain().focus().toggleStrike().run()" :class="btnClass(editor.isActive('strike'))">Tachado</button>
            <button type="button" @click="editor.chain().focus().toggleBulletList().run()" :class="btnClass(editor.isActive('bulletList'))">Lista</button>
            <button type="button" @click="editor.chain().focus().toggleOrderedList().run()" :class="btnClass(editor.isActive('orderedList'))">Lista numerada</button>
            <button type="button" @click="editor.chain().focus().toggleBlockquote().run()" :class="btnClass(editor.isActive('blockquote'))">Cita</button>
            <button type="button" @click="editor.chain().focus().toggleCodeBlock().run()" :class="btnClass(editor.isActive('codeBlock'))">Código</button>
            <button type="button" @click="editor.chain().focus().setHorizontalRule().run()">Línea</button>
            <button type="button" @click="addLink">Enlace</button>
            <button type="button" @click="addImage">Imagen</button>
            <button type="button" @click="editor.chain().focus().undo().run()">Deshacer</button>
            <button type="button" @click="editor.chain().focus().redo().run()">Rehacer</button>
        </div>
        <editor-content :editor="editor" class="wysiwyg-content" />
    </div>
</template>

<script setup>
import { Editor, EditorContent } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';
import Link from '@tiptap/extension-link';
import Image from '@tiptap/extension-image';
import Underline from '@tiptap/extension-underline';
import { computed, onBeforeUnmount, watch } from 'vue';

const props = defineProps({
    modelValue: { type: String, default: '' },
});
const emit = defineEmits(['update:modelValue']);

const editor = new Editor({
    extensions: [StarterKit, Link, Image, Underline],
    content: props.modelValue,
    onUpdate: ({ editor }) => {
        emit('update:modelValue', editor.getHTML());
    },
});

watch(() => props.modelValue, (val) => {
    if (val !== editor.getHTML()) {
        editor.commands.setContent(val || '', false);
    }
});

onBeforeUnmount(() => editor.destroy());

const btnClass = (active) =>
    'px-2 py-1 text-sm rounded border ' + (active ? 'bg-black text-white border-black' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100');

const addLink = () => {
    const url = window.prompt('URL');
    if (url) editor.chain().focus().extendMarkRange('link').setLink({ href: url }).run();
};
const addImage = () => {
    const url = window.prompt('Image URL');
    if (url) editor.chain().focus().setImage({ src: url }).run();
};
</script>

<style scoped>
.wysiwyg-content :deep(.ProseMirror) {
    min-height: 200px;
    padding: 12px;
    outline: none;
    border: 1px solid #e5e7eb;
    border-radius: 0.375rem;
}
.wysiwyg-content :deep(.ProseMirror:focus) {
    outline: none;
    border-color: #9ca3af;
}
.wysiwyg-content :deep(.ProseMirror ul) { list-style: disc; padding-left: 1.5rem; }
.wysiwyg-content :deep(.ProseMirror ol) { list-style: decimal; padding-left: 1.5rem; }
</style>



