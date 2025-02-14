<template>
    
    <q-toolbar class="shadow-1">
        <span v-if="title" class="gt-xs">{{ title ?? 'My cockpit' }}</span>
        <q-space />
        <q-tabs class="text-weight-regular" active-color="primary" shrink stretch>
            
            <!-- Shortcuts -->
            <q-route-tab to="/cockpit/dashboard" icon="dashboard" />

            <!-- Setting -->
            <q-route-tab to="/cockpit/profile" label="My Cockpit" />
        </q-tabs>
    </q-toolbar>

</template>

<script>
export default {
    name: 'NavCockpit',
    props: {
        title: String,
    },
};
</script>
