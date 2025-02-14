<template>

    <PageWrapper :rendering="loading" >
        <template #navigation>
            <NavUser />
        </template>

        <!-- Price -->
        <div class="row w-100 justify-center">
            <PricesTable
                class="table-width" 
                :prices="prices"
                @action="(price) => openPaymentGateway(price.price_token)"
                @cancel="(price) => confirmCancelSubscription(price)"
            />
            <SectionNote>
                By purchasing tokens through payments, you are able to gain access to our provided app features.<br>
                For further information see our <router-link to="/pricing">Pricing details</router-link> 
                and <router-link to="/legal">Terms &amp; Conditions</router-link>.
            </SectionNote>
        </div>
        

        <!-- Transactions -->
        <div class="row w-100 justify-center q-my-md">
            <q-separator class="table-width" />
        </div>
        <div class="row w-100 justify-center">
            <TransactionsTable class="table-width" :transactions="transactions"/>
            <SectionNote>
                Payment History: Each transaction represents a payment made from your access token purchases.
            </SectionNote>
        </div>
        
        <!-- Paddle -->
        <PaddlePriceJS 
            @paddleEvents="(data) => paddleEventHandling(data)"
            @loaded="(PaddleCheckout) => this.Paddle = PaddleCheckout"
        />
    </PageWrapper>

</template>

<script>
import { ref } from 'vue';
import PaddlePriceJS from 'src/pages/access/components/PaddlePriceJS.vue';
import PricesTable from './components/UserPricesTable.vue';
import TransactionsTable from './components/UserTransactionsTable.vue';

export default {
    name: 'UserAccess',
    components: {
        PaddlePriceJS, TransactionsTable, PricesTable
    },

    setup() {

        // Defaults
        const loading = ref(true);
        const showDialog = ref(false);
        const intervalRequests = ref(0);
        const intervalRequestLimit = 9;

        // Paddle Checkout
        const Paddle = ref(null);
        const openPaymentGateway = (priceToken) => {
            Paddle.value?.Checkout.open({
                settings: {
                    showAddDiscounts: false,
                    allowLogout: false,
                    // successUrl: 'URL'
                },
                items: [{ 
                    priceId: priceToken, 
                    quantity: 1 
                }],
            });
        };

        return {
            loading,
            showDialog,
            intervalRequests,
            intervalRequestLimit,
            openPaymentGateway,
            Paddle
        };
    },

    data() {
        return {
            prices: [],
            transactions: [],
        }
    },

    async mounted() {
        this.loadAccess();
    },

    methods: {

        async loadAccess(){
            try {
                this.loading = true;
                const response = await this.$axios.get('load-user-access')
                this.prices = response.data.prices;
                this.transactions = response.data.transactions;
            } catch (error) {
                this.$toast.error(error.response ?? error);
                console.log('user.access.error', error.response ?? error)
            } finally {
                this.loading = false;
            }
        }, 

        // Client checkout completed
        // Initialize client checkout
        async paddleEventHandling(data) {
            try {
                if(data?.name === 'checkout.completed') {
                    const transactionID = data.data?.transaction_id;
                    const customerID = data.data?.customer?.id;
                    await this.$axios.post('initialize-user-checkout', {
                        transaction_token: transactionID,
                        customer_token: customerID
                    });

                    // Verify transaction by webhook interval
                    this.checkTransactionWebhookVerificationInterval(transactionID)
                }
            } catch (error) {
                this.$toast.error(error.response ?? error)
                console.log('user.checkout.error', error.response ?? error)
            }
        },

        // Set interval a 5sec
        // Verify transaction and set user-access
        checkTransactionWebhookVerificationInterval(transactionID) {
            const intervalId = setInterval(async () => {
                try {
                    // Max amount of request
                    if(this.intervalRequests > this.intervalRequestLimit) 
                        throw 'Verification in progress. May this takes a few minutes.'

                    // Request
                    this.intervalRequests++;
                    const response = await this.$axios.post('verify-user-checkout', {
                        'transaction_token': transactionID,
                    });

                    // Check new access set
                    const access = response.data.access
                    if(access?.access_token) {
                        this.$user.setAppAccess(
                            access.access_token, 
                            access.expiration_date,
                            access.quantity 
                        );

                        // Check if its a subscription
                        const priceID = response.data.price_id;
                        this.prices.forEach((price, index) => {
                            if(price.id === priceID)
                                this.prices[index].has_access = access; 
                            if(price.id === priceID && price.is_subscription) 
                                this.prices[index].has_active_subscription = true; 
                        });

                        // Clear interval
                        this.$toast.success(response.data.message);
                        clearInterval(intervalId);
                    }
                } catch (error) {
                    clearInterval(intervalId);
                    this.intervalRequests = 0;
                    this.$toast.error(error.response ?? error)
                    console.log('user.transaction.verification.error', error.response ?? error);
                }
            }, 5000);
        },

        // Cancel user subscription
        // if price-type === 'subscription'
        async cancelSubscription(price) {
            try {
                this.$toast.load();
                const response = await this.$axios.post('cancel-user-subscription' , {
                    'price_token': price.price_token,
                });
                this.$toast.success(response.data.message);
                price.has_active_subscription = false;
            } catch (error) {
                this.$toast.error(error.response ?? error);
                console.log('subscription.cancel.error', error.response ?? error)
            }
        },

        confirmCancelSubscription(price) {
            this.$q.dialog({
                title: 'Cancel: ' + price.name,
                message: 'Sure you want to cancel your subscription? You will not have access to the provided service after latest transaction expires.',
                cancel: true,
            }).onOk(() => {
                this.cancelSubscription(price)
            })
        },
    },
};
</script>
