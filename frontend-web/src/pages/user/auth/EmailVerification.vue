<template>
    <PageWrapper>
        <CardSimple 
            class="w-card q-my-lg" 
            title="Email Verification"
            note="*After verify your email, you will be logged into your account."
        >
            <q-card-section>
                <FormWrapper
                    buttonText="Verify account"
                    buttonIcon="verified"
                    @submit="makeValidationRequest(password, password_confirm)"
                >
                    <!-- Email -->
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

                    <!-- Set New Password -->
                    <p>Choose your password:</p>
                    <div>
                        <q-input
                            filled
                            type="password"
                            v-model="password"
                            label="Enter password"
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
                            filled
                            type="password"
                            v-model="password_confirm"
                            label="Confirm password"
                        >
                            <template v-slot:prepend>
                                <q-icon name="lock" />
                            </template>
                        </q-input>
                    </div>
                </FormWrapper>
            </q-card-section>
        </CardSimple>
    </PageWrapper>

</template>

<script>
import PasswordCheck from 'components/PasswordCheck.vue';

export default {
    name: 'EmailVerification',
    components: {
        PasswordCheck
    },

    emits: [
        'setSession'
    ],

    data() {
        return {
            email: this.$route.params.email,
            key: this.$route.params.key,
            password: '',
            password_confirm: '',
        };
    },

    methods: {
        async makeValidationRequest(pw, pw_confirm) {
            try {
                // Verify Password
                const passwordCheck = this.$globals.checkPasswordRequirements(pw, pw_confirm);
                if(passwordCheck) throw passwordCheck;
                this.$user.removeBearerToken();
                
                // Request
                this.$toast.load();
                const response = await this.$axios.put(this.$route.fullPath, {
                    'password': pw,
                    'password_confirmation': pw_confirm,
                });
                
                // Login
                this.$toast.success(response.data.message)
                this.$user.setBearerToken(response.data.token);
                this.$emit('setSession', '/user');
            } catch (error) {
                this.$toast.error(error.response ?? error);
            } finally {
                this.password = '';
                this.password_confirm = '';
            }
        }
    }
};
</script>
