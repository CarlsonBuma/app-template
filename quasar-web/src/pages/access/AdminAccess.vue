<template>

    <PageWrapper :rendering="loading" >
        <template #navigation>
            <NavAdmin />
        </template>

        <!-- Price -->
        <div class="row w-100 justify-center">
            <PricesTable 
                class="table-width"
                title="Manage prices"
                :prices="prices"
                @update="(price) => updatePrice(price.id, price.is_active)"
            />
            <SectionNote>
                Public available prices enable users to purchase access to specific features within our app.<br>
                Prices can be set within Paddle Cockpit. For further information, please refer to the documentation.
            </SectionNote>
        </div>

        <!-- Access -->
        <div class="row w-100 justify-center q-my-md">
            <q-separator class="table-width" />
        </div>
        <div class="row w-100 justify-center">
            <AccessTable 
                class="table-width"
                title="User access"
                :access="userAccess"
                @search="(email) => serachUser(email)"
                @add="(email) => prepareAddNewAccess(email)"
                @update="(access) => updateAccess(access.id, access.is_active)"
            />
        </div>

        <!-- Transactions -->
        <div class="row q-mt-md w-100 justify-center">
            <TransactionsTable
                class="table-width" 
                title="User transactions"
                :transactions="userTransactions"
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
import PricesTable from './components/AdminPricesTable.vue';
import AccessTable from './components/AdminAccessTable.vue';
import TransactionsTable from './components/AdminTransactionsTable.vue';

export default {
    name: 'AdminAccess',
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
                const response = await this.$axios.get('get-app-prices')
                this.prices = response.data.prices;
            } catch (error) {
                this.$toast.error(error.response ?? error);
                console.log('admin.prices.error', error.response ?? error)
            } finally {
                this.loading = false;
            }
        }, 

        async updatePrice(priceID, status){
            try {
                this.$toast.load();
                const response = await this.$axios.post("/update-app-price", {
                    price_id: priceID,
                    is_active: status
                });
                this.$toast.success(response.data.message)
            } catch (error) {
                this.$toast.error(error.response ?? error);
                console.log('admin.user.access.error', error.response ?? error)
            }
        }, 

        async serachUser(email) {
            try {
                if(!email) throw 'Email field is required.'
                this.$toast.load();
                const response = await this.$axios.get("/get-app-user-access", { params: { 
                    email: email
                }});

                this.userAccess = response.data.latest_access;
                this.userTransactions = response.data.transactions;
                this.$toast.success(response.data.message)
            } catch (error) {
                this.$toast.error(error.response ?? error);
                console.log('admin.user.access.error', error.response ?? error)
            }
        },

        async updateAccess(accessID, status){
            try {
                this.$toast.load();
                const response = await this.$axios.post("/update-app-user-access", {
                    access_id: accessID,
                    is_active: status
                });
                this.$toast.success(response.data.message)
            } catch (error) {
                this.$toast.error(error.response ?? error);
                console.log('admin.user.access.error', error.response ?? error)
            }
        }, 

        async createNewAccess(newAccess, requestedAccessEmail) {
            try {
                if(!requestedAccessEmail) throw 'Invalid email.'
                if(!newAccess.access_token) throw 'Access toke is required.'
                if(!newAccess.expiration_date) throw 'Expiration date is required.'
                if(!newAccess.quantity || newAccess.quantity === 0) throw 'Value must be bigger than 0.'

                this.$toast.load();
                const response = await this.$axios.post("/create-app-user-access", {
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
                console.log('admin.user.access.error', error.response ?? error)
            }
        }
    },
};
</script>
