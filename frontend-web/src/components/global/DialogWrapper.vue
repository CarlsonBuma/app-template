<style scoped>
.btn-dialog-wrapper {
    position: absolute; 
    top: 0;
    right: 0; 
    z-index: 9999999 !important;
}
</style>


<template>

    <div class="absolute w-100"> <!-- Div Required! -->
        <q-dialog 
            :full-width="fullWidth"
            :model-value="showDialog"
            :maximized="(!minimized && $q.screen.lt.sm) || maximized"
            @hide="$emit('close', showDialog = false)"
            :position="position"
            :class="scopedClass"
        >
            <div class="w-card items-center justify-center flex" @click.self="showDialog = false"> 
                
                <!-- Close -->
                <div class="btn-dialog-wrapper q-pa-md">
                    <q-btn icon="close" dense flat v-close-popup />
                </div>
                
                <q-card class="w-100">
                    <!-- Header -->
                    <q-card-section 
                        v-if="title"
                        class="" 
                        :class="$tp.client_preferences.value.darkmode ? 'bg-dark text-white' : 'bg-grey-1 text-dark'"
                    >
                        <span class="text-overline">{{ title }}</span>
                    </q-card-section>

                    <!-- Content -->
                    <q-separator v-if="title" class="w-100"/>
                    <slot />
                </q-card>
            </div>
        </q-dialog>
    </div>
    
</template>

<script>
export default {
    name: 'DialogWrapper',

    props: {
        scopedClass: String,
        maximized: Boolean,
        fullWidth: Boolean,
        minimized: Boolean,
        title: String,
        position: String,
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
