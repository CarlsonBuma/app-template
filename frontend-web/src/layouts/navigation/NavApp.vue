<template>

    <q-list>

        <!-- Header -->
        <q-item class="q-py-md">
            <q-item-section>
                <q-item-label overline>{{ $user.user.name ? 'Dashboard' : $env.APP_NAME }}</q-item-label>
                <q-item-label caption v-if="$user.user.name">@{{ $user.user.name }}</q-item-label>
            </q-item-section>
        </q-item>
        <q-separator />

        <!-- Login -->
        <q-item 
            clickable 
            v-close-popup 
            @click="goMemberArea()" 
            v-if="!$user?.access?.user"
        >
            <q-item-section avatar>
                <q-icon name="login" />
            </q-item-section>
            <q-item-section>
                <span class="q-ml-xs cursor-pointer">Login</span>
            </q-item-section>
        </q-item>

        <!-- User -->
        <q-item 
            clickable
            v-if="$user?.access?.user"
            @click="$router.push('/user')"  
        >
            <q-item-section avatar>
                <q-icon name="dashboard" />
            </q-item-section>
            <q-item-section>
                <q-item-label>Dashbaord</q-item-label>
            </q-item-section>
        </q-item>
        
        <!-- Account -->
        <q-item v-if="$user?.access?.user">
            <q-item-section>
                <q-item-label caption>
                    My Account
                </q-item-label>
            </q-item-section>
        </q-item>
        <q-separator v-if="$user?.access?.user"/>
        <q-item 
            clickable
            v-if="$user?.access?.user"
            @click="$router.push('/account/access')"  
        >
            <q-item-section avatar>
                <q-icon name="generating_tokens" />
            </q-item-section>
            <q-item-section>
                <q-item-label>App Access</q-item-label>
            </q-item-section>
        </q-item>
        <q-item 
            clickable
            v-if="$user?.access?.user"
            @click="$router.push('/account/settings')"  
        >
            <q-item-section avatar>
                <q-icon name="settings" />
            </q-item-section>
            <q-item-section>
                <q-item-label>Settings</q-item-label>
            </q-item-section>
        </q-item>

        <!-- Logout -->
        <q-item 
            clickable
            v-if="$user?.access?.user"
            @click="$emit('logout')"
        >
            <q-item-section avatar>
                <q-icon name="logout" />
            </q-item-section>
            <q-item-section>
                <q-item-label>Logout</q-item-label>
            </q-item-section>
        </q-item>

        <!-- Admin -->
        <!-- Backpanel -->
        <q-item 
            clickable v-ripple
            v-if="$user?.access?.tokens[accessToken]" 
            @click="$router.push('/admin/dashboard')"
        >
            <q-item-section avatar>
                <q-icon name="admin_panel_settings" class="q-mr-sm" />
            </q-item-section>
            <q-item-section>
                <q-item-label>Backpanel</q-item-label>
            </q-item-section>
        </q-item>

        <!-- About -->
        <q-item>
            <q-item-section>
                <q-item-label caption>Our Plattform</q-item-label>
            </q-item-section>
        </q-item>
        <q-separator />
        <q-item clickable v-close-popup @click="$router.push('/')">
            <q-item-section avatar>
                <q-icon name="home" />
            </q-item-section>
            <q-item-section>
                <span class="q-ml-xs cursor-pointer">Home</span>
            </q-item-section>
        </q-item>
        <q-item clickable v-close-popup @click="$router.push('/newsfeed')">
            <q-item-section avatar>
                <q-icon name="campaign" />
            </q-item-section>
            <q-item-section>
                <span class="q-ml-xs cursor-pointer">Newsfeed</span>
            </q-item-section>
        </q-item>
        <q-item clickable v-close-popup @click="$router.push('/about')">
            <q-item-section avatar>
                <q-icon name="store" />
            </q-item-section>
            <q-item-section>
                <span class="q-ml-xs cursor-pointer">About us</span>
            </q-item-section>
        </q-item>
        <q-item clickable v-close-popup @click="$router.push('/legal')">
            <q-item-section avatar>
                <q-icon name="policy" />
            </q-item-section>
            <q-item-section>
                <span class="q-ml-xs cursor-pointer">Legal &amp; Compliance</span>
            </q-item-section>
        </q-item>
    </q-list>

</template>

<script>
export default {
    name: 'NavUser',
    props: {
        title: String,
    },

    components: {
        // 
    },

    emits: [
        'setSession',
        'logout',
        
    ],

    setup() {
        return {
            accessToken: process.env.APP_ACCESS_ADMIN,
        };
    },

    methods: {
        async goMemberArea() {
            if(!this.$user.checkBearerTokenSet()) this.$router.push('/login')
            else this.$emit('setSession')
        }
    }
};
</script>
