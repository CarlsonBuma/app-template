<template>    
    
    <div id="app-wrapper">
        <div id="app-design"></div>
        <q-layout view="hhh LpR fff">
            
            <!-- Header Top -->
            <q-header 
                reveal
                elevated 
                height-hint="98"
                class="text-white"
                :class="{
                    'bg-dark': $q.dark.isActive,
                    'bg-header-bright-mode': !$q.dark.isActive,
                }"
            >
                <NavHead 
                    :loading="loading"
                    @authUser="(route) => authUser(route)"
                    @logoutUser="logoutUser()"
                    @logoClick="$showDrawer.value = !$showDrawer.value"
                />
                <q-separator color="white" />
            </q-header>

            <!-- Content -->
            <q-page-container 
                id="app-content"
                :class="{
                    'background': !$q.dark.isActive,
                    'background-dark': $q.dark.isActive
                }"
            >
                <router-view 
                    @authorize="(route) => authUser(route)"
                    @removeSession="removeSession()"
                />
            </q-page-container>
        </q-layout>
    </div>
    
</template>

<script>
import { ref } from 'vue';
import NavHead from 'src/components/navigation/NavHead.vue';


export default {
    name: 'App',
    components: {
        NavHead
    },

    setup() {
        const loading = ref(false);
        return {
            loading,
        }
    },

    mounted() {
        // Code
    },

    methods: {
        async authUser(route) {
            try {
                // Check Session Storage
                // Bearer Token - OAuth2
                this.loading = true;
                if(!this.$user.checkBearerTokenSet()) throw 'No client token.'
                if(!this.$user.access.user && this.$user.checkBearerTokenSet()) {
                    
                    // Authorize User
                    this.$user.setSession();
                    this.$toast.load('Authorizing...')
                    const response = await this.$axios.get('/auth');
                    this.$user.setUser(
                        response.data.user.id, 
                        response.data.user.name, 
                        response.data.user.avatar, 
                        response.data.user.email, 
                    );

                    // Set app access
                    response.data.access.forEach(access => {
                        this.$user.setAppAccess(
                            access.access_token, 
                            access.expiration_date,
                            access.quantity
                        )
                    })
                }
                
                // Redirect if requested
                this.$toast.success('Session started.')
                if(route === 'back') this.$router.go(-1);
                else if(route) this.$router.push(route)
            } catch (error) {
                if(error.response) {
                    this.$toast.error(error.response ?? error)
                    this.$router.push('/login')
                }
            } finally {
                this.$toast.done();
                this.loading = false;
            }
        },

        async logoutUser() {
            try {
                this.$toast.load();
                const response = await this.$axios.post('/logout');
                this.$toast.success(response.data.message);
                this.removeSession();
            } catch (error) {
                console.log('app.logout', error.response ?? error)
            } finally {
                this.$toast.done()
            }
        },

        removeSession() {
            this.$user.removeBearerToken();
            this.$user.removeSession();
            this.$router.push('/');
        },

        showLogs() {
            console.log('Cookie', this.$cc);
            console.log('Quasar', this.$q);
            console.log('Axios', this.$axios);
            console.log('ENV', this.$env);
            console.log('Store', this.$user);
            console.log('Toast', this.$toast);
        }
    },
};
</script>
