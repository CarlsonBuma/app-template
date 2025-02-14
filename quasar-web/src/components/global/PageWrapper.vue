<template>
    
    <div id="page-content">
        <q-page id="page-wrapper" :class="{'bg-design': bgDesign}">

            <!-- Navigation -->
            <div 
                v-if="!disableNav"
                :class="$q.dark.isActive 
                    ? 'text-sm bg-dark text-white' 
                    : 'bg-grey-1 text-dark'"
            >
                <slot name="navigation">
                    <NavUser v-if="$user.access.user" />
                </slot>
            </div>
            
            <!-- Content -->
            <q-pull-to-refresh 
                class="w-100" 
                :disable="!allowRefresh" 
                @refresh="(done) => refresh(done)" 
            >
                <div 
                    class="row justify-center" 
                    :class="noMargin ? '' : 'q-px-md-md q-py-xl'" 
                >
                    <LoadingData 
                        v-if="rendering"
                        text="Processing data..."
                        :colorIcon="bgDesign ? 'white' : 'primary'"
                        :colorText="bgDesign ? 'text-white' : 'text-grey'"
                    />

                    <!-- Content -->
                    <div class="flex justify-center w-100" v-else >
                        <slot />
                    </div>
                </div>
            </q-pull-to-refresh>
        </q-page>

        <!-- Footer -->
        <q-footer
                id="app-footer"
                bordered 
                v-if="showFooter"
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
import NavFoot from 'src/components/navigation/NavFoot.vue';

export default {
    name: 'PageWrapper',
    components: {
        NavFoot
    },

    emits: [
        'refresh',
    ],

    props: {
        allowRefresh: Boolean,      // Reloading Site
        rendering: Boolean,         // Render content
        loading: Boolean,           // Loading data

        // Navigation
        showFooter: Boolean,
        disableNav: Boolean,
        leftDrawer: Boolean,
        
        // Design
        bgDesign: Boolean,
        noMargin: Boolean,
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
