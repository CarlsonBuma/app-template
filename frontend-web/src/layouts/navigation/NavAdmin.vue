<template>

    <q-toolbar class="shadow-1">
        <span class="gt-xs">{{ title ?? 'Backpanel' }}</span>
        <q-space />
        <q-tabs class="text-weight-regular" active-color="primary" shrink stretch>
            <q-route-tab to="/admin/dashboard" exact icon="dashboard" />
            <q-route-tab to="/admin/newsfeed" exact label="Newsfeed" />
            <q-route-tab to="/admin/access" exact label="App Access" />
            <q-route-tab to="/admin/app-settings" exact label="Settings" />
        </q-tabs>
    </q-toolbar>

</template>

<script>
export default {
    name: 'NavAdmin',
    props: {
        title: String,
    },
};
</script>
