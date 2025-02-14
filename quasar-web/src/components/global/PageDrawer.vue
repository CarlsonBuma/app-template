<style lang="sass">
.q-drawer--left
    box-shadow: $shadow-1
</style>

<template>
    
    <q-drawer 
        show-if-above
        :side="side ?? 'left'"
        :mini="fixed ? false : $drawerLeft.value"
        :width="280"
        v-model="showDrawerLeft"
        @hide="$drawerLeft.value = false"
    >
        <q-scroll-area class="fit">
            
            <!-- Handle Drawer -->
            <q-list padding bordered >
                <q-item>
                    <q-item-section avatar >
                        <q-btn
                            dense flat v-close-popup
                            color="primary" 
                            :icon="$drawerLeft.value || fixed ? 'keyboard_arrow_right' : 'keyboard_arrow_left'" 
                            @click="$drawerLeft.value = !$drawerLeft.value" 
                        />
                    </q-item-section>
                    <q-item-section>
                        {{ title ?? 'Dashboard' }}
                    </q-item-section>
                </q-item>
            </q-list>

            <!-- Content -->
            <slot />
        </q-scroll-area>
    </q-drawer>

</template>

<script>
export default {
    name: 'PageWrapper',

    props: {
        fixed: Boolean,
        side: String,
        title: String,
    },

    data() {
        return {
            showDrawerLeft: true,
        };
    },

    watch: {
        // If lower than Width- MD, we do not want Mini-Mode
        // Instead We want to Show / Hide DrawerLeft
        '$drawerLeft.value'(value) {
            this.showDrawerLeft = true;
            if(window.innerWidth < 1024) this.showDrawerLeft = value;
        }
    },
};
</script>
