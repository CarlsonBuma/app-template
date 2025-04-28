<template>

    <q-editor
        flat
        v-model="content"
        :readonly="readOnly"
        :toolbar="!readOnly ? [
            ['bold', 'italic', 'strike', 'underline', 'subscript', 'superscript'],
            ['quote', 'unordered', 'ordered', 'outdent', 'indent'],
            ['undo', 'redo'],
            ['fullscreen'],
        ] : []"
    />
    
</template>

<script>
export default {
    name: 'CardFocus',
    props: {
        readOnly: Boolean,
        modelValue: {
            type: String,
            default: ''
        }
    },

    computed: {
        content: {
            get() {
                return this.modelValue ?? '';
            },
            set(value) {
                this.$emit('update:modelValue', value);
            }
        }
    },

    emits: [
        'update:modelValue',
    ],
};
</script>
