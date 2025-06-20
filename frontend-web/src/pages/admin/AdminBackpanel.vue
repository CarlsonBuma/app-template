<template>

    <PageWrapper :rendering="rendering" >
        <template #navigation>
            <NavAdmin />
        </template>

        <q-card class="my-card">
            <q-card-section>
                <div class="text-h6">Verified Users:</div>
                <div class="text-h3 q-ma-md flex justify-center">{{ infos.users ? infos.users : 0 }}</div>
            </q-card-section>

            <q-separator />

            <q-card-actions align="right">
                <q-btn flat>Manage</q-btn>
            </q-card-actions>
        </q-card>
    </PageWrapper>

</template>

<script>
import { ref } from 'vue';

export default {
    name: 'AdminBackpanel',
    components: {
        // 
    },

    setup() {
        return {
            rendering: ref(true),
            infos: ref({})
        };
    },

    mounted() {
        this.getBackpanelInfos();
    },

    methods: {
        async getBackpanelInfos() {
            try {
                this.rendering = true;
                const response = await this.$axios.get("/admin-backpanel");
                this.infos = response.data;
                this.$toast.success('Welcome to Backpanel.');
            } catch (error) {
                this.$toast.error(error.response)
            } finally {
                this.rendering = false;
            }
        }
    },
};
</script>
