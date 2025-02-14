<template>

    <q-toolbar class="shadow-1">
        <span v-if="title" class="gt-xs">{{ title ?? 'My Avatar' }}</span>
        <q-space />
        <q-tabs class="text-weight-regular" active-color="primary" shadow shrink stretch>
            
            <!-- Shortcuts -->
            <q-route-tab to="/user/dashboard" exact icon="dashboard" />
            
            <!-- Profile -->
            <q-btn-dropdown auto-close stretch flat label="My Avatar">
                <q-list>
                    <q-item clickable @click="$router.push('/account/access')">
                        <q-item-section>My Access</q-item-section>
                    </q-item>
                    <q-item clickable @click="$router.push('/account/settings')">
                        <q-item-section>Settings</q-item-section>
                    </q-item>
                </q-list>
            </q-btn-dropdown>
        </q-tabs>
    </q-toolbar>

</template>

<script>
export default {
    name: 'NavUser',
    props: {
        title: String,
    },
};
</script>
