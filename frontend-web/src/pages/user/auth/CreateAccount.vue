<template>

    <PageWrapper>
        <CardSimple goBack class="w-card" title="Create Account" >
            
            <!-- Registration -->
            <q-card-section>
                <FormWrapper
                    buttonText="Register"
                    buttonIcon="admin_panel_settings"
                    @submit="registerUser(user.name, user.email, user.agreed)"
                >
                    <!-- Username -->
                    <q-input filled v-model="user.name" label="Username">
                        <template v-slot:prepend>
                            <q-icon name="person" />
                        </template>
                    </q-input>

                    <!-- Email -->
                    <q-input filled type="email" v-model="user.email" label="Email" >
                        <template v-slot:prepend>
                            <q-icon name="mail" />
                        </template>
                    </q-input>
                </FormWrapper>
            </q-card-section>

            <!-- Terms & Conditions -->
            <q-separator />
            <q-card-section>
                <p>Please agree our Terms-of-use:</p>
                <div class="flex items-center">
                    <q-checkbox v-model="user.agreed.terms"/>I agree with&nbsp;
                    <router-link target="_blank" to="/legal">Terms &amp; Conditions</router-link>.
                </div>
                <div class="flex items-center">
                    <q-checkbox v-model="user.agreed.privacy" />I agree with&nbsp;
                    <router-link target="_blank" to="/legal">Data Privacy</router-link>.
                </div>
            </q-card-section>
        </CardSimple>
    </PageWrapper>

</template>

<script>
import { ref } from 'vue';

export default {
    name: 'CreateNewAccount',
    components: {
        // 
    },

    setup() {
        return {
            loading: ref(false),
        };
    },

    data() {
        return {
            user: {
                name: '',
                email: '',
                agreed: {
                    terms: false,
                    privacy: false,
                },
            },
        };
    },
    
    methods: {
        async registerUser(name, email, agreed) {
            try {
                // Validate
                if(!name) throw 'Please enter a name.';
                else if (!agreed.terms || !agreed.privacy) throw 'Please agree with our terms-of-use.';
                
                // Create User
                this.$toast.load();
                const response = await this.$axios.post("/create-account", {
                    'name': name,
                    'email': email,
                    'terms': agreed.terms,
                    'privacy': agreed.privacy,
                });

                // Redirect User to Verify Email
                this.$toast.success(response.data.message);
                this.$router.push({
                    name: 'EmailVerificationRequest', 
                    params: { 
                        email: email,
                    }
                });
            } catch (error) {
                this.$toast.error(error.response ?? error);
            } finally {
                this.$toast.done();
            }
        },
    }
};
</script>
