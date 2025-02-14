<template>

    <PageWrapper>
        <CardWrapper
            class="w-card-lg"
            title="Transfer account"
            iconHeader="switch_account"
            note="*After verify your account, you will be able to login with your new credentials."
        >
            <FormWrapper
                buttonText="Transfer Account"
                buttonIcon="supervisor_account"
                @submit="makeValidationRequest(password, password_confirm, agreed)"
            > 
                <!-- From Email -->  
                <p>Transfer from:</p>
                <q-input
                    v-model="email"
                    type="email"
                    filled
                    readonly
                >
                    <template #prepend>
                        <q-icon name="email" />
                    </template>
                </q-input>

                <!-- Token -->
                <q-input
                    v-model="key"
                    class="q-mt-none"
                    filled
                    disable
                    readonly
                >
                    <template #prepend>
                        <q-icon name="key" />
                    </template>
                </q-input>

                <!-- To Email -->
                <p>To:</p>
                <q-input
                    v-model="transfer"
                    type="email"
                    filled
                    readonly
                >
                    <template #prepend>
                        <q-icon name="email" />
                    </template>
                </q-input>

                <!-- Set New Password -->
                <p>Choose your password:</p>
                <div>
                    <q-input
                        label="Enter password"
                        v-model="password"
                        type="password"
                        filled
                    >
                        <!-- Icon -->
                        <template v-slot:prepend>
                            <q-icon name="lock" />
                        </template>
                        
                        <!-- Validation -->
                        <template v-slot:append>
                            <q-icon name="info">
                                <q-tooltip>
                                    <PasswordCheck
                                        :password="password"
                                        :password_confirm="password_confirm"
                                    />
                                </q-tooltip>
                            </q-icon>
                        </template>
                    </q-input>
                    <q-input
                        v-model="password_confirm"
                        label="Confirm password"
                        type="password"
                        filled
                    >
                        <template v-slot:prepend>
                            <q-icon name="lock" />
                        </template>
                    </q-input>
                </div>

                <!-- Terms & Conditions -->
                <div class="q-pa-sm">
                    <p>Please agree "Terms-of-use":</p>
                    <div class="flex items-center">
                        <q-checkbox v-model="agreed.terms"/>I agree with&nbsp;
                        <router-link target="_blank" to="/legal">Terms &amp; Conditions</router-link>.
                    </div>
                    <div class="flex items-center">
                        <q-checkbox v-model="agreed.privacy" />I agree with&nbsp;
                        <router-link  target="_blank" to="/legal">Data Privacy</router-link>.
                    </div>
                </div>
            </FormWrapper>
        </CardWrapper>
    </PageWrapper>

</template>

<script>
import { ref } from 'vue';
import CardWrapper from 'components/CardWrapper.vue';
import PasswordCheck from 'components/PasswordCheck.vue';

export default {
    name: 'TransferAccount',
    components: {
        CardWrapper, PasswordCheck
    },

    emits: [
        'authorize'
    ],

    setup() {
        return {
            loading: ref(false),
            showTerms: ref(false)
        };
    },
    
    data() {
        return {
            email: this.$route.params.email,
            key: this.$route.params.key,
            transfer: this.$route.params.transfer,
            password: '',
            password_confirm: '',
            agreed: {
                terms: false,
                privacy: false,
            }
        };
    },
    
    methods: {
        async makeValidationRequest(pw, pw_confirm, agreed) {
            try {
                // Validate
                const passwordCheck = this.$globals.checkPasswordRequirements(pw, pw_confirm);
                if(passwordCheck) throw passwordCheck;
                if(!agreed.terms || !agreed.privacy) throw 'Please agree to our terms-of-use.'
                
                // Transfer
                // Fullpath includes Token to verify its user
                this.$toast.load();
                const response = await this.$axios.put(this.$route.fullPath, {
                    'password': pw,
                    'password_confirmation': pw_confirm,
                    'terms': agreed.terms,
                    'privacy': agreed.privacy
                });
                
                // Login
                this.$toast.success(response.data.message)
                this.$user.setBearerToken(response.data.token);
                this.$emit('authorize', '/user/dashboard');
                this.password = '';
                this.password_confirm = '';
            } catch (error) {
                this.$toast.error(error.response ?? error);
            }
        }
    }
};
</script>
