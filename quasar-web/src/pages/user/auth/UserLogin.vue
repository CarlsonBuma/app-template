<template>

    <PageWrapper>
        <CardWrapper
            class="w-card"
            title="Login"
            iconHeader="verified_user"
            goBack
        >
            <!-- Google Sign-In -->
            <div class="text-center q-pt-sm q-pb-lg">
                <q-btn 
                    outline 
                    color="primary" 
                    label="Sign-In with Google" 
                    @click="redirectToGoogle"  
                />
            </div>

            <!-- Login -->
            <FormWrapper
                buttonText="Login"
                buttonIcon="verified_user"
                @submit="loginUser(login.email, login.password)"
            >
                <q-input filled v-model="login.email" type="email" label="Enter email" />
                <q-input filled v-model="login.password" type="password" label="Enter password" />
            </FormWrapper>
            <template #footer>
                <q-separator />
                <q-card-section>
                    <div class="row">
                        <q-btn @click="$router.push('password-reset-request')" flat class="col-12" label="Reset password" />
                        <q-btn @click="$router.push('create-account')" flat class="col-12" label="Create an account" />
                        <q-btn @click="$router.push('legal')" flat class="col-12" label="Terms &amp; Conditions" />
                    </div>
                </q-card-section>
            </template>
        </CardWrapper>
    </PageWrapper>

</template>

<script>
import { openURL } from 'quasar';
import CardWrapper from 'components/CardWrapper.vue';
import FormWrapper from 'src/components/global/FormWrapper.vue';

export default {
    name: 'UserLogin',
    components: {
         CardWrapper, FormWrapper
    },

    emits: [
        'authorize'
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
                this.$toast.load();
                const response = await this.$axios.post("/login", {
                    'email': this.login.email,
                    'password': this.login.password,
                });
                
                // Login
                this.$user.setBearerToken(response.data.token);
                this.$emit('authorize', '/user');
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
