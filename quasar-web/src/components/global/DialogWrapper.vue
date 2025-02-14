<template>

    <div class="absolute"> <!-- Div Required! -->
        <q-dialog 
            :full-width="fullWidth"
            :model-value="showDialog"
            :maximized="$q.screen.lt.sm || maximized"
            @hide="$emit('close', showDialog = false)"
        >
            <q-card class="w-card">
                <q-card-section class="row items-center q-pb-none">
                    <div class="text-subtitle1">{{ title }}</div>
                    <q-space />
                    <q-btn icon="close" flat round dense v-close-popup />
                </q-card-section>
                <q-separator class="q-mt-md"/>
                <slot />
            </q-card>
        </q-dialog>
    </div>
    
</template>

<script>
export default {
    name: 'DialogWrapper',

    props: {
        maximized: Boolean,
        fullWidth: Boolean,
        title: String,
        modelValue: {
            type: Boolean,
            required: true
        }
    },

    computed: {
        showDialog: {
            get() {
                return this.modelValue;
            },
            set(value) {
                this.$emit('update:modelValue', value);
            }
        }
    },
    
    emits: [
        'update:modelValue',
        'close'
    ],
};
</script>
