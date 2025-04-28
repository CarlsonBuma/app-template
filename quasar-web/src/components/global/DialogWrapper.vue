<style scoped>
.btn-dialog-wrapper {
    position: fixed; 
    top: 0; 
    right: 0; 
    z-index: 999;
    margin: '12px'
}
</style>

<template>

    <div class="absolute"> <!-- Div Required! -->
        <q-dialog 
            :full-width="fullWidth"
            :model-value="showDialog"
            :maximized="$q.screen.lt.sm || maximized"
            @hide="$emit('close', showDialog = false)"
        >
            <q-card class="w-card">
                <q-card-section 
                    class="row items-center q-pb-none q-mb-md" 
                    :class="$tp.client_preferences.value.darkmode ? 'bg-dark text-white' : 'bg-grey-1 text-dark'"
                >
                    <div class="text-subtitle1">{{ title }}</div>
                    <q-space />
                    <div>
                        
                    </div>
                    <q-btn class="btn-dialog-wrapper q-ma-sm" icon="close" dense flat v-close-popup />
                </q-card-section>
                <q-separator class="w-100"/>
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

    mounted() { 
        // this.componentRendered = true;
    },
    
    emits: [
        'update:modelValue',
        'close'
    ],
};
</script>
