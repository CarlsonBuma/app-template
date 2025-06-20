<template>
    <q-layout view="lHh LpR lff">
        
        <q-header elevated>
            <q-toolbar>
                <NavHead
                    @logoClick="toggleLeftDrawer"
                />
                <q-separator color="white" />
            </q-toolbar>
        </q-header>

        <q-drawer
            v-model="leftDrawerOpen"
            show-if-above
            bordered
        >
            <NavCockpit />
        </q-drawer>

        <!-- Content -->
        <q-page-container>
            <router-view />
        </q-page-container>
    </q-layout>
</template>

<script>
import { defineComponent, ref } from 'vue'
import NavHead from './navigation/NavHead.vue'
import NavCockpit from './navigation/NavCockpit.vue'

export default defineComponent({
  name: 'CockpitLayout',

    components: {
        NavHead, NavCockpit
    },

    setup () {
        const leftDrawerOpen = ref(false)

        return {
            leftDrawerOpen,
            toggleLeftDrawer () {
                leftDrawerOpen.value = !leftDrawerOpen.value
            }
        }
    },

    methods: {
        //
    },
})
</script>
