<template>

    <PageWrapper>
        <CardWrapper
            class="w-card-lg"
            title="Login"
            iconHeader="verified_user"
            goBack
        >
            <FormWrapper
                buttonText="Login"
                buttonIcon="verified_user"
                @submit="loginUser(login.email, login.password)"
            >
                <q-input v-model="login.email" type="email" label="Enter email" />
                <q-input v-model="login.password" type="password" label="Enter password" />
            </FormWrapper>

            <!-- Auth -->
            <q-separator class="q-my-md" />
            <div class="row">
                <q-btn @click="$router.push('password-reset-request')" flat class="col-12" label="Reset password" />
                <q-btn @click="$router.push('create-account')" flat class="col-12" label="Create an account" />
                <q-btn @click="$router.push('legal')" flat class="col-12" label="Terms &amp; Conditions" />
            </div>
        </CardWrapper>
    </PageWrapper>

</template>

<script>
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
                this.$emit('authorize', '/user/dashboard');
            } catch (error) {
                // No Error processing
                this.$toast.error(error.response ?? error);
            } finally {
                this.login.password = '';
            }
        }
    }
};
</script>
