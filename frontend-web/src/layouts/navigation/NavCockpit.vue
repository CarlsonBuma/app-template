<template>

    <q-list>

        <!-- Header -->
        <q-item class="q-py-md">
            <q-item-section avatar>
                <q-btn dense flat icon="arrow_left" @click="$router.push('/user')"/>
            </q-item-section>
            <q-item-section>
                <q-item-label overline>Business Cockpit</q-item-label>
                <q-item-label caption>Controll Panel</q-item-label>
            </q-item-section>
        </q-item>
        <q-separator />

        <q-item 
            clickable
            @click="$router.push('/cockpit')"  
        >
            <q-item-section avatar>
                <q-icon name="dashboard" />
            </q-item-section>
            <q-item-section>
                <q-item-label>Dashboard</q-item-label>
            </q-item-section>
        </q-item>
        <q-item 
            clickable
            v-if="$user?.access?.user"
            @click="$router.push('/cockpit/impressum')"  
        >
            <q-item-section avatar>
                <q-icon name="contacts" />
            </q-item-section>
            <q-item-section>
                <q-item-label>Impressum</q-item-label>
            </q-item-section>
        </q-item>
    </q-list>

</template>

<script>
export default {
    name: 'NavAdmin',
    props: {
        title: String,
    },
};
</script>
