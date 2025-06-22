<template>
    <q-layout view="lHh LpR lff">
        
        <q-header 
            elevated
            :class="{
                'bg-dark': $q.dark.isActive,
                'bg-header-bright-mode': !$q.dark.isActive,
            }"
        >
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
            <NavAdmin />
        </q-drawer>

        <!-- Content -->
        <q-page-container
            id="app-content"
            :class="{
                'background': !$q.dark.isActive,
                'background-dark': $q.dark.isActive
            }"
        >
            <router-view />
        </q-page-container>
    </q-layout>
</template>

<script>
import { defineComponent, ref } from 'vue'
import NavHead from './navigation/NavHead.vue'
import NavAdmin from './navigation/NavAdmin.vue'

export default defineComponent({
  name: 'AdminLayout',

    components: {
        NavHead, NavAdmin
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
