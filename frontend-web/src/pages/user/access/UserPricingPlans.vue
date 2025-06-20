<template>

    <LoadingData v-if="loading" :colorIcon="color" :colorText="color"/>
    <q-markup-table v-else class="w-100" wrap-cells>
        <thead>
            <tr>
                <th class="text-left custom-tr-width">Subscription Plans</th>
                <th class="text-center custom-tr-width-sm">Free</th>
                <th class="text-center custom-tr-width-sm">Basic</th>
                <th class="text-center custom-tr-width-sm">Establishment</th>
                <th class="text-center custom-tr-width-sm">Promotion</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-left">
                    <span class="text-overline">Community Hosting</span><br>
                    <span class="text-caption">Build your community and share amazing Locations. Keep your Members up-to-date about upcomming Events.</span>
                </td>
                <td class="text-center">✅</td>
                <td class="text-center">✅</td>
                <td class="text-center">✅</td>
                <td class="text-center">✅</td>
            </tr>
            <tr>
                <td class="text-left">
                    <span class="text-overline">Event Promotion</span><br>
                    <span class="text-caption">Build your community and share amazing Locations. Together, we're creating an ecosystem that highlights extraordinary locations and hidden gems around the world.</span>
                </td>
                <td class="text-center"><b>12 Events</b><br>in Total</td>
                <td class="text-center"><b>12 Events</b><br>per Year</td>
                <td class="text-center"><b>99 Events</b><br>per Year</td>
                <td class="text-center"><b>999 Events</b><br>per Year</td>
            </tr>
            <tr>
                <td class="text-left">
                    <span class="text-overline">Cloud Storage</span><br>
                    <span class="text-caption">Show off amazing places, highlight moments, and bring your community to life with immersive content! 🤖</span>
                </td>
                <td class="text-center">1 GB</td>
                <td class="text-center">5 GB</td>
                <td class="text-center">20 GB</td>
                <td class="text-center">120 GB</td>
            </tr>
            <tr>
                <td class="text-left">
                    <span class="text-overline">Pricing</span><br>
                    <span class="text-caption">All subscription fees are billed annually, ensuring uninterrupted access to your chosen services.</span>
                </td>
                <td class="text-center">Free</td>
                <td class="text-center"><b>CHF 49.90</b><br>per Year</td>
                <td class="text-center"><b>CHF 99.90</b><br>per Year</td>
                <td class="text-center"><b>CHF 199.90</b><br>per Year</td>
            </tr>

            <!-- Access -->
            <tr v-if="$user?.access?.user" class="q-tr--no-hover">
                <td class="text-left">
                    <span class="text-overline">Service Access</span><br>
                        <span class="text-caption">Select your subscription plan here!</span>
                </td>

                <!-- Free Plan -->
                <td class="text-center">
                    <q-btn 
                        :loading="loadingFreeAccess" 
                        :label="$user.access?.tokens[this.accessToken] ? 'Access Granted' : 'Get Access'" 
                        :outline="$user.access?.tokens[this.accessToken] ? true : false"
                        :disabled="$user.access?.tokens[this.accessToken] ? true : false"
                        color="primary" 
                        @click="requestFreeAccess()"
                    />
                </td>

                <!-- Prices -->
                <td class="text-center" v-for="price, index in prices" :key="index">
                    <PaddlePriceJS 
                        :index="index"
                        :priceID="price.price_token"
                        :price="price"
                        :activeSubscription="price.has_active_subscription"
                        :disable="hasCommunitySubscription"
                        @checkoutVerified="(access) => {
                            price.has_access = access;
                            price.has_active_subscription = true;
                            hasCommunitySubscription = true;
                        }"
                        @subscriptionCanceled="{
                            price.has_access = null;
                            price.has_active_subscription = false;
                            hasCommunitySubscription = false;
                        }"
                    />
                </td>
            </tr>

            <!-- Metrics -->
            <tr v-if="$user?.access?.user">
                <td colspan="5"><span class="text-caption"><b>Current Access Metrics</b></span></td>
            </tr>
            <tr v-if="$user?.access?.user" class="q-tr--no-hover">
                <td class="text-center" colspan="1">
                    <div class="q-my-md">
                        <span class="text-overline">Renewal</span><br>
                        <span class="text-h5">{{ $tp.date($user.access?.tokens[this.accessToken]?.expiration_date) ?? 'Never' }}</span>
                    </div>
                </td>
                <td class="text-center" colspan="2">
                    <span class="text-overline">Events left</span><br>
                    <span class="text-h5">{{ $user.access?.tokens[this.accessToken]?.quantity ?? 0 }}</span>
                </td>
                <td class="text-center" colspan="2">
                    <span class="text-overline">Storage</span><br>
                    <span class="text-h5">
                        {{ storageUsage ?? 0 }} / {{ $user.access?.tokens[this.accessToken]?.limit_storage ?? 0 }}GB
                    </span>
                </td>
            </tr>

            <!-- Login -->
            <tr v-else class="q-tr--no-hover">
                <td colspan="5" >
                    <div class="w-100 flex justify-center q-pa-sm">
                        <q-btn 
                            flat 
                            :disable="$user?.access.user" 
                            color="primary" 
                            icon="auto_awesome" 
                            @click="$router.push('/login')" 
                            :label="$user?.access.user ? 'Access granted' : 'Login'" 
                        />
                    </div>
                </td>
            </tr>
        </tbody>
    </q-markup-table>

</template>

<script>
import { ref } from 'vue';
import PaddlePriceJS from 'components/PaddlePriceJS.vue';

export default {
    name: 'UserPricingPlans',
    components: {
        PaddlePriceJS
    },

    props: {
        color: String,
    },

    emits: [
        'paddleEvents',
        'loaded'
    ],

    watch: {
        // Waiting on App initialization, as a public route
        // Triggers as soon user is authenticated
        '$user.access.user': {
            handler(newValue) {
                if (newValue) {
                    this.initUserPrices();
                }
            },
            immediate: true     
        }
    },

    setup() {
        return {
            loading: ref(false),
            loadingFreeAccess: ref(false),
            accessToken: process.env.APP_ACCESS_COCKPIT,
        }
    },

    data() {
        return {
            prices: [],
            hasCommunitySubscription: false,
            communityAccess: {},
            storageUsage: 0,
        }
    },

    mounted() {
        // 
    },

    methods: {
        async initUserPrices() {
            try {
                this.loading = true;
                const response = await this.$axios.get("/user-load-pricing-plans", { params: {
                    'access_token': this.accessToken
                }});

                this.prices = response.data.prices;
                this.hasCommunitySubscription = response.data.has_community_subscription
                this.communityAccess = response.data.community_access
                this.storageUsage = response.data.storage_usage
            } catch (error) {
                this.$toast.error(error.response ?? error)
            } finally {
                this.loading = false;
            }
        },

        async requestFreeAccess() {
            try {
                this.loadingFreeAccess = true;
                const response = await this.$axios.put("/user-free-community-access");
                const accessToken = response.data.access;
                this.$toast.success(response.data.message)
                this.$user.setAppAccess(accessToken)
            } catch (error) {
                this.$toast.error(error.response ?? error)
            } finally {
                this.loadingFreeAccess = false;
            }
        },
    },
};
</script>
