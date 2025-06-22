<template>

    <PageWrapper >

        <!-- Header -->
        <template #header>
            <q-tabs v-model="tab" >
                <q-tab name="access" label="App Access" />
                <q-tab name="transactions" label="Payment History" />
            </q-tabs>
            <q-separator class="w-100" />
        </template>

        <q-tab-panels class="bg-transparent w-content-md" v-model="tab" animated>

            <!-- Prices -->
            <q-tab-panel name="access" class="flex justify-center q-pa-none">
                <UserPricingPlans class="w-content-md-md"/>
                <SectionNote>
                    By purchasing tokens through payments, you are able to gain access to our provided app features.<br>
                    For further information see our <router-link to="/ecosystem">Pricing details</router-link> 
                    and <router-link to="/legal">Terms &amp; Conditions</router-link>.
                </SectionNote>
            </q-tab-panel>

            <!-- Transactions -->
            <q-tab-panel name="transactions" class="flex justify-center q-pa-none">
                <TransactionsTable class="table-width"/>
                <SectionNote>
                    History of all transactions associated with your app access.<br>
                    You can update, manage, or cancel your subscription anytime from your account settings.
                </SectionNote>
            </q-tab-panel>
        </q-tab-panels>
    </PageWrapper>

</template>

<script>
import { ref } from 'vue';
import TransactionsTable from './access/UserTransactionsTable.vue';
import UserPricingPlans from 'src/pages/user/access/UserPricingPlans.vue';

export default {
    name: 'UserAccess',
    components: {
        TransactionsTable, UserPricingPlans
    },

    setup() {
        return {
            tab: ref('access'),
        };
    },

    data() {
        return {
            // 
        }
    },

    async mounted() {
        // 
    },

    methods: {
        //
    },
};
</script>
