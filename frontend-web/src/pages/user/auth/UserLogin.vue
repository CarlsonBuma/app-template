<template>

    <PageWrapper>
        <CardSimple goBack class="w-card q-my-lg" title="Login" >

            <q-card-section class="flex justify-center">
                <q-btn 
                    outline 
                    color="primary" 
                    label="Sign-In with Google" 
                    @click="redirectToGoogle"  
                />
            </q-card-section>
            
            <!-- Login -->
            <q-separator />
            <q-card-section>
                <FormWrapper
                    buttonText="Login"
                    buttonIcon="verified_user"
                    @submit="loginUser(login.email, login.password)"
                >
                    <q-input filled v-model="login.email" type="email" label="Enter email" />
                    <q-input filled v-model="login.password" type="password" label="Enter password" />
                </FormWrapper>
            </q-card-section>

            <!-- Account Access -->
            <q-separator />
            <q-card-section class="row">
                <q-btn 
                    flat class="col-12" 
                    label="Reset password"
                    @click="$router.push('password-reset-request')" 
                />
                <q-btn 
                    flat 
                    class="col-12" 
                    label="Create New Account"
                    @click="$router.push('create-account')" 
                />
                <q-btn 
                    flat 
                    class="col-12" 
                    label="Terms &amp; Conditions"
                    @click="$router.push('legal')" 
                />
            </q-card-section>
        </CardSimple>
    </PageWrapper>

</template>

<script>
import { openURL } from 'quasar';

export default {
    name: 'UserLogin',
    components: {
        //
    },

    emits: [
        'setSession'
    ],

    setup () {
        const googleLogin = process.env.APP_SERVER_URL + "/google-auth/redirect/web"
        return {
            googleLogin,
            openURL,
        }
    },
    
    data() {
        return {
            login: {
                email: '',
                password: '',
            }
        };
    },
    
    methods: {
        async loginUser(email, password) {
            try {
                if(!password || !email) throw "Please enter credentials."
                this.$user.removeBearerToken();
                this.$toast.load();
                const response = await this.$axios.post("/login", {
                    'email': this.login.email,
                    'password': this.login.password,
                });
                
                // Login
                this.$user.setBearerToken(response.data.token);
                this.$emit('setSession', '/user');
            } catch (error) {
                // No Error processing
                this.$toast.error(error.response ?? error);
            } finally {
                this.login.password = '';
            }
        },

        redirectToGoogle() {
            window.location.href = this.googleLogin; // Redirect in the same tab
        },
    }
};
</script>
