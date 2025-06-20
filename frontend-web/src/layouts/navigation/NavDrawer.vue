<template>
    
    <q-drawer 
        class="shadow-1"
        :show-if-above="!close"
        :side="side ?? 'left'"
        :mini="fixed ? false : $showDrawer.value"
        :width="width ?? 280"
        v-model="drawerStatus"
        @hide="$showDrawer.value = false"
    >
        <q-scroll-area class="fit">
            
            <!-- Handle Drawer -->
            <q-list dense padding >
                <q-item>
                    <q-item-section>
                        <q-item-label overline>{{ title ?? $env.APP_NAME }}</q-item-label>
                    </q-item-section>
                    <q-item-section avatar>
                        <q-btn
                            dense flat v-close-popup
                            color="primary" 
                            :icon="$showDrawer.value || fixed ? 'keyboard_arrow_right' : 'keyboard_arrow_left'" 
                            @click="$showDrawer.value = !$showDrawer.value" 
                        />
                    </q-item-section>
                </q-item>
            </q-list>

            <!-- Content -->
            <q-separator />
            <slot />
        </q-scroll-area>
    </q-drawer>

</template>

<script>
export default {
    name: 'NavDrawer',

    props: {
        title: String,
        width: Number,
        side: String,
        noHead: Boolean,

        // Drawer Visibility
        fixed: Boolean,     // Show or Hidden Drawer - no mini view
        show: Boolean,      // Show Drawer by default, in fixed visibilty
        close: Boolean,     // Allow hide Drawer in fixed visibilty
    },

    data() {
        return {
            drawerStatus: this.show,
        };
    },

    watch: {
        // If lower than Width- MD, we do not want Mini-Mode
        // Instead We want to Show / Hide showDrawer
        '$showDrawer.value'(value) {
            if (window.innerWidth < 1024 || this.close) this.drawerStatus = value;
            if (window.innerWidth >= 1024 && this.show) this.drawerStatus = true;
        }
    },

    mounted() {
        this.$showDrawer.value = this.show;
    },
};
</script>
