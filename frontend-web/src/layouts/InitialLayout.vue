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
            <NavApp 
                @setSession="(route) => $emit('setSession', route)"
                @logout="$emit('logout')"
            />
        </q-drawer>

        <!-- Content -->
        <q-page-container
            id="app-content"
            :class="{
                'background': !$q.dark.isActive,
                'background-dark': $q.dark.isActive
            }"
        >
            <router-view 
                @setSession="(route) => $emit('setSession', route)"
                @removeSession="$emit('removeSession')"
            />
        </q-page-container>
    </q-layout>
</template>

<script>
import { defineComponent, ref } from 'vue'
import NavHead from './navigation/NavHead.vue'
import NavApp from './navigation/NavApp.vue'

export default defineComponent({
  name: 'InitialLayout',

    components: {
        NavHead, NavApp
    },

    emits: [
        'setSession',
        'removeSession',
        'logout'
    ],

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
