<template>

    <PageWrapper>
        <CardSimple
            class="w-card q-my-lg"
            title="Password Reset"
            iconHeader="lock_person"
        >
            <!-- Token -->
            <q-card-section>
                <q-input filled v-model="email" readonly >
                    <template #prepend>
                        <q-icon name="email" />
                    </template>
                </q-input>
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
            </q-card-section>

            <!-- New Password -->
            <q-separator />
            <q-card-section>
                <FormWrapper
                    buttonText="Set password"
                    buttonIcon="password"
                    @submit="setUserPassword(password, password_confirm)"
                >
                    <!-- Set Password -->
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
import FormWrapper from 'src/components/global/FormWrapper.vue';
import PasswordCheck from 'components/PasswordCheck.vue';

export default {
    name: 'PasswordSet',
    components: {
        FormWrapper, PasswordCheck
    },

    data() {
        return {
            email: this.$route.params.email,
            key: this.$route.params.key,
            password: '',
            password_confirm: ''
        };
    },
    methods: {
        async setUserPassword(password, password_confirm) {
            try {
                // Validate
                const passwordCheck = this.$globals.checkPasswordRequirements(password, password_confirm);
                if(passwordCheck) throw passwordCheck;
                this.$user.removeBearerToken();
                
                // Request
                this.$toast.load();
                const response = await this.$axios.put(this.$route.fullPath, {
                    'password': password,
                    'password_confirmation': password_confirm
                });
                this.$toast.success(response.data.message);

                // Login
                this.$user.setBearerToken(response.data.token);
                this.$emit('setSession', '/user');
                this.password = '';
                this.password_confirm = '';
            } catch (error) {
                this.$toast.error(error.response ?? error);
            }
        },
    }
};
</script>
