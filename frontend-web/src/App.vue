<template>
    <router-view 
        id="app-content"
        @setSession="(route) => authUser(route)"
        @removeSession="removeSession()"
        @logout="logoutUser()"
    />
</template>

<script>
import { defineComponent } from 'vue';

export default defineComponent({
    name: 'App',

    methods: {
        async authUser(route) {
            try {
                // Check Session Storage
                // Bearer Token - OAuth2
                this.loading = true
                if(!this.$user.checkBearerTokenSet()) throw 'No client token.'
                if(!this.$user.access.user && this.$user.checkBearerTokenSet()) {
                    
                    // Authorize User
                    this.$user.setSession()
                    this.$toast.load('Authorizing...')
                    const response = await this.$axios.get('/user')
                    this.$user.setUser(
                        response.data.user.id, 
                        response.data.user.name, 
                        response.data.user.avatar, 
                        response.data.user.email, 
                    );

                    // Set app access
                    response.data.access.forEach(access => {
                        this.$user.setAppAccess(access)
                    })
                }
                
                // Redirect if requested
                this.$toast.success('Session started.')
                if(route === 'back') this.$router.go(-1)
                else if(route) this.$router.push(route)
            } catch (error) {
                if(error.response) {
                    this.$toast.error(error.response ?? error)
                    this.$router.push('/login')
                }
            } finally {
                this.$toast.done()
                this.loading = false
            }
        },

        async logoutUser() {
            try {
                this.$toast.load()
                const response = await this.$axios.post('/logout')
                this.$toast.success(response.data.message)
                this.removeSession()
            } catch (error) {
                console.log('error.app.logout', error.response ?? error)
            } finally {
                this.$toast.done()
            }
        },

        removeSession() {
            this.$user.removeBearerToken()
            this.$router.push('/')
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
});
</script>
