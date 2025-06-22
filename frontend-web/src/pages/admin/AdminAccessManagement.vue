<template>

    <PageWrapper :rendering="loading" >

        <template #header>
            <q-tabs v-model="tab" >
                <q-tab name="access" label="Access Management" />
            </q-tabs>
            <q-separator class="w-100"/>
        </template>

        <!-- Price -->
        <div class="w-content">
            <PricesTable 
                class="w-100"
                title="App Prices"
                :prices="prices"
                @update="(price) => updatePrice(price.id, price.is_active)"
            />
            <SectionNote>
                Public available prices enable users to purchase access to specific features within our app.<br>
                Prices can be set within Paddle Cockpit. For further information, please refer to the documentation.
            </SectionNote>
        </div>

        <!-- Access -->
        <q-separator class="w-content q-mb-md" />
        <AccessTable 
            class="w-content"
            title="User Access"
            :access="userAccess"
            @search="(email) => searchUser(email)"
            @add="(email) => prepareAddNewAccess(email)"
            @update="(access) => updateAccess(access.id, access.is_active)"
        />

        <!-- Transactions -->
        <div class="w-content">
            <TransactionsTable
                class="w-100" 
                title="User Transactions"
                :transactions="userTransactions"
                @cancel="(transaction) => cancelTransaction(transaction)"
            />
            <SectionNote>
                Transactions are payments initiated either by "one-time purchases" or "subscriptions" charged periodically.<br>
                These transactions correspond to our provided prices, granting users access to specific app features.
            </SectionNote>
        </div>

        <!-- Add new access-->
        <DialogWrapper v-model="showAddUserAccessPopup" title="Add user access">
            <q-card-section>
                <span>Grant access to: <b>{{ requestedAccessEmail }}</b></span>
            </q-card-section>
            <q-separator/>
            <q-card-section>
                <span>
                    Define access token to allow users to access certain app features. 
                    Tokens are defined within the app. For more information, please refer to the documentation.
                </span>
                <q-input label="Enter access token" v-model="newAccess.access_token" />
                <div class="text-caption q-pt-sm q-pl-sm">
                    <span><b>Existing tokens:</b></span>
                    <ul>
                        <li><u>Price tokens</u>: 'access-cockpit'</li>
                        <li><u>Private tokens</u>: 'access-admin'</li>
                    </ul>
                </div>
            </q-card-section>
            <q-separator/>
            <q-card-section>
                <span>
                    The duration and quantity define the access limits. Depending on the logic, 
                    the quantity may represent credits, or the expiration date may define the period of access.
                </span>
                <q-input v-model="newAccess.expiration_date" label="Define expiration date" type="date" />
                <q-input v-model="newAccess.quantity" label="Define quantity" type="number" />
            </q-card-section>
            <q-separator/>
            <q-card-section class="text-right">
                <q-btn 
                    icon="token"
                    color="primary" 
                    label="Create access" 
                    @click="createNewAccess(newAccess, requestedAccessEmail)"
                />
            </q-card-section>
        </DialogWrapper>
    </PageWrapper>

</template>

<script>
import { ref } from 'vue';
import PricesTable from './access/AdminPricesTable.vue';
import AccessTable from './access/AdminAccessTable.vue';
import TransactionsTable from './access/AdminTransactionsTable.vue';

export default {
    name: 'AdminAccessManagement',
    components: {
        PricesTable, AccessTable, TransactionsTable
    },

    setup() {
        const loading = ref(true);
        const showAddUserAccessPopup = ref(false);
        const requestedAccessEmail = ref('');

        const prepareAddNewAccess = (email) => {
            showAddUserAccessPopup.value = true;
            requestedAccessEmail.value = email; 
        }

        return {
            tab: ref('access'),
            loading,
            showAddUserAccessPopup,
            requestedAccessEmail,
            prepareAddNewAccess,
        };
    },

    data() {
        return {
            prices: [],
            userAccess: [],
            userTransactions: [],
            newAccess: {},
        }
    },

    async mounted() {
        this.loadPrices();
    },

    methods: {

        async loadPrices(){
            try {
                this.loading = true;
                const response = await this.$axios.get('admin-load-prices')
                this.prices = response.data.prices;
            } catch (error) {
                this.$toast.error(error.response ?? error);
            } finally {
                this.loading = false;
            }
        }, 

        async updatePrice(priceID, status){
            try {
                this.$toast.load();
                const response = await this.$axios.post("/admin-update-app-price", {
                    price_id: priceID,
                    is_active: status
                });
                this.$toast.success(response.data.message)
            } catch (error) {
                this.$toast.error(error.response ?? error);
            }
        }, 

        async searchUser(email) {
            try {
                if(!email) throw 'Email field is required.'
                this.$toast.load();
                const response = await this.$axios.get("/admin-get-user-access", { params: { 
                    email: email
                }});

                this.userAccess = response.data.latest_access;
                this.userTransactions = response.data.transactions;
                this.$toast.success(response.data.message)
            } catch (error) {
                this.$toast.error(error.response ?? error);
            }
        },

        async updateAccess(accessID, status){
            try {
                this.$toast.load();
                const response = await this.$axios.post("/admin-update-user-access", {
                    access_id: accessID,
                    is_active: status
                });
                this.$toast.success(response.data.message)
            } catch (error) {
                this.$toast.error(error.response ?? error);
            }
        }, 

        async createNewAccess(newAccess, requestedAccessEmail) {
            try {
                if(!requestedAccessEmail) throw 'Invalid email.'
                if(!newAccess.access_token) throw 'Access toke is required.'

                this.$toast.load();
                const response = await this.$axios.post("/admin-create-user-access", {
                    email: requestedAccessEmail,
                    access_token: newAccess.access_token,
                    quantity: newAccess.quantity,
                    expiration_date: newAccess.expiration_date
                });

                this.userAccess.unshift(response.data.access);
                this.newAccess = {};
                this.showAddUserAccessPopup = false;
                this.$toast.success(response.data.message);
            } catch (error) {
                this.$toast.error(error.response ?? error);
            }
        },

        async cancelTransaction(transaction) {
            try {
                this.$toast.load();
                const response = await this.$axios.post("/admin-cancel-user-transaction", {
                    transaction_id: transaction.id,
                });

                this.$toast.success(response.data.message)
                transaction.canceled_at = response.data.canceled_at
            } catch (error) {
                this.$toast.error(error.response ?? error);
            }
        }
    },
};
</script>
