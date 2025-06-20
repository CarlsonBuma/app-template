<template>

    <!-- Initialize Paddle Checkout-->
    <div class="q-px-md">

        <!-- Purchase -->
        <q-btn 
            class="btn-subscribe" 
            :label="activeSubscription ? 'Active' : 'Demand Access'" 
            color="primary"
            :disable="disable || activeSubscription"
            :outline="activeSubscription ? true : false"
            :loading="loadCheckout"
            @click="initializePaddle(priceID)" 
        />

        <!-- Cancel subscriptions -->
        <q-btn 
            v-if="activeSubscription"
            outline
            class="w-100 q-mt-sm" 
            label="Cancel"
            color="purple"
            size="sm"
            @click="confirmCancelSubscription(priceID)"
        />
    </div>
    
</template>

<script>
import { ref } from 'vue';
import { initializePaddle } from '@paddle/paddle-js';

/**
 * Dependency: PaddleJS instance
 * https://developer.paddle.com/
 * 
 * Setup:
 * Initialize Paddle instance
 */
export default {
    name: 'PaddlePriceJS',
    components: {
        //
    },

    props: {
        priceID: String,
        price: Object,
        activeSubscription: Boolean,
        disable: Boolean
    },

    emits: [
        'initialized',
        'beforeCheckout',
        'checkoutCompleted',
        'checkoutVerified',
        'subscriptionCanceled',
        'errorVerification',
    ],

    setup() {
        const loadCheckout = ref(false);
        const intervalID = ref(null);
        const intervalRequests = ref(0);
        const intervalRequestLimit = 20;

        const PaddleCheckout = ref(null);
        const openPaymentGateway = (priceID) => {
            PaddleCheckout.value?.Checkout.open({
                settings: {
                    showAddDiscounts: false,
                    allowLogout: false,
                    // successUrl: 'URL'
                },
                items: [{ 
                    priceId: priceID, 
                    quantity: 1 
                }],
            });
        };

        return {
            intervalID,
            loadCheckout,
            intervalRequests,
            intervalRequestLimit,
            PaddleCheckout,
            openPaymentGateway,
        };
    },

    data() {
        return {
            //
        }
    },

    mounted() {
        
    },

    methods: {

        // Initialize Paddle
        async initializePaddle(priceID) {
            try {
                this.PaddleCheckout = await initializePaddle({
                    environment: process.env.APP_PADDLE_ENVIRONMENT
                        ? process.env.APP_PADDLE_ENVIRONMENT
                        : 'sandbox',
                    token: process.env.APP_PADDLE_CLIENT_KEY,
                    eventCallback: (data) => this.paddleEventHandling(data)
                });
                
                if (this.PaddleCheckout) {
                    this.$emit('initialized', this.PaddleCheckout)
                    this.openPaymentGateway(priceID)
                }
            } catch (error) {
                console.error('error.initializing.paddle', error);
            }
        },

        // Client checkout completed
        // Initialize client checkout
        async paddleEventHandling(data) {
            try {
                this.$emit('beforeCheckout', data)
                if(data?.name === 'checkout.completed') {
                    this.loadCheckout = true;
                    const transactionID = data.data?.transaction_id;
                    const customerID = data.data?.customer?.id;
                    await this.$axios.post('user-initialize-checkout', {
                        transaction_token: transactionID,
                        customer_token: customerID
                    });

                    // Verify transaction by webhook interval
                    this.loadCheckout = false;
                    this.$emit('checkoutCompleted', data)
                    this.checkTransactionWebhookVerificationInterval(transactionID)
                }
            } catch (error) {
                this.loadCheckout = false;
                this.$toast.error(error.response ?? error)
            }
        },

        // Verify transaction and set user-access
        checkTransactionWebhookVerificationInterval(transactionID) {
            
            // Check
            this.intervalRequests = 0;
            this.intervalID = null;
            this.loadCheckout = true;
            this.$toast.success('We are processing... Please wait!')
            
            // Interval a 3sec
            this.intervalID = setInterval(async () => {

                try {
                    // Max amount of request
                    if(this.intervalRequests > this.intervalRequestLimit) 
                        throw 'Error processing transaction.'

                    // Request
                    this.intervalRequests++;
                    const response = await this.$axios.post('user-verify-checkout', {
                        'transaction_token': transactionID,
                    });

                    // Check new access set
                    const access = response.data?.access
                    if(access?.access_token && this.intervalID) {
                        this.$user.setAppAccess(access);
                        this.$toast.success(response.data.message);
                        this.$emit('checkoutVerified', access)
                        this.loadCheckout = false;
                        clearInterval(this.intervalID);
                        this.intervalID = null;
                    }
                } catch (error) {
                    this.loadCheckout = false;
                    this.intervalRequests = 0;
                    this.$emit('errorVerification', error.response ?? error)
                    this.$toast.error(error.response ?? error)
                    clearInterval(this.intervalID);
                    this.intervalID = null;
                }
            }, 3500);
        },

        // Cancel user subscription
        // if price-type === 'subscription'
        async cancelSubscription(priceID) {
            try {
                this.$toast.load();
                const response = await this.$axios.post('user-cancel-subscription' , {
                    'price_token': priceID,
                });

                this.$toast.success(response.data.message);
                const communityAccess = response.data.community_access;
                this.$user.setAppAccess(communityAccess);
                this.$emit('subscriptionCanceled')
            } catch (error) {
                this.$toast.error(error.response ?? error);
            }
        },

        confirmCancelSubscription(priceID) {
            this.$q.dialog({
                title: 'Cancel Subscription',
                message: 'Are you sure you want to cancel your subscription? Once canceled, your access will revert to the Free Plan, and you will lose all benefits associated with your current subscription until renewal.',
                cancel: true,
            }).onOk(() => {
                this.cancelSubscription(priceID)
            })
        },
    },
};
</script>


