<style scoped>
.page-wrapper-navigation-fixed {
    z-index: 999;
    left:auto; 
    right: auto;
}
</style>

<template>
    
    <div id="page-content" :class="{'bg-design': bgDesign}">
        
        <!-- Top Navigation -->
        <div :class="$q.dark.isActive ? 'text-sm bg-dark text-white' : 'bg-grey-1 text-dark'" >
            <slot name="navigation" />
        </div>
        
        <!-- Content -->
        <q-pull-to-refresh 
            class="w-100" 
            :disable="!allowRefresh" 
            @refresh="(done) => refresh(done)" 
        >
            <!-- Header -->
            <slot name="header" />

            <!-- Content -->
            <div class="w-100 flex justify-center" >
                
                <!-- Rendering -->
                <LoadingData 
                    v-if="rendering"
                    class="q-my-lg"
                    text="Processing data..."
                    :colorIcon="bgDesign ? 'white' : 'primary'"
                    :colorText="bgDesign ? 'text-white' : 'text-grey'"
                />

                <!-- Content -->
                <slot v-else />
            </div>
        </q-pull-to-refresh>

        <!-- Bottom -->
        <slot name="bottom" />

        <!-- Fixed Navigation -->
        <div class="fixed-bottom page-wrapper-navigation-fixed">
            <slot name="navigation-fixed" />
        </div>

        <!-- Footer -->
        <q-footer
            id="app-footer"
            v-if="showFooter"
            bordered 
            :class="{
                'bg-dark': $q.dark.isActive,
                'bg-grey-1': !$q.dark.isActive,
                'text-white': $q.dark.isActive,
                'text-dark': !$q.dark.isActive,
            }"
        >
            <NavFoot />
        </q-footer>
    </div>

</template>

<script>
import { QSpinnerGears } from 'quasar'
import NavFoot from 'layouts/navigation/NavFoot.vue'

export default {
    name: 'PageWrapper',
    components: {
        NavFoot
    },

    emits: [
        'refresh',
    ],

    props: {
        rendering: Boolean,         // Render content
        loading: Boolean,           // Loading data
        allowRefresh: Boolean,      // Reloading Site

        // Navigation
        navBottom: Boolean,
        showFooter: Boolean,
        
        // Design
        bgDesign: Boolean,
        noMargin: Boolean,
        navBottomClass: String,
    },

    setup() {
        return {
            //
        };
    },

    watch: {
        loading(value) {
            value ? this.startRendering() : this.stopRendering();
        },
    },

    methods: {

        refresh(done) {
            this.$emit('refresh');
            done();
        },

        startRendering() {
            this.$q.loading.show({
                boxClass: 'page-loading-block',
                spinner: QSpinnerGears,
                message: 'Loading data. Please wait...',
            })
        },

        stopRendering() {
            this.$q.loading.hide()
        }
    }
};
</script>
