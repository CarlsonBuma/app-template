<template>

    <PageWrapper>
        <CardSimple
            class="w-card q-my-lg"
            title="Reset Password"
            note="You will receive the link to reset your password via provided email."
            goBack
        >
            <q-card-section>
                <FormWrapper
                    buttonText="Send Token"
                    buttonIcon="send"
                    @submit="resetPasswordRequest(email)"
                >
                    <q-input
                        filled
                        type="email"
                        label="Enter email"
                        v-model="email"
                    >
                        <template #prepend>
                            <q-icon name="email" />
                        </template>
                    </q-input>
                </FormWrapper>
            </q-card-section>
            
        </CardSimple>
    </PageWrapper>

</template>

<script>
import FormWrapper from 'src/components/global/FormWrapper.vue';

export default {
    name: 'PasswordReset',
    components: {
        FormWrapper
    },

    data() {
        return {
            email: this.$route.params.email,
        };
    },

    methods: {
        async resetPasswordRequest(email) {
            try {
                if(!email) throw 'Please enter your email.'
                this.$toast.load();
                const response = await this.$axios.post("/password-reset-request", {
                    'email': email
                });
                this.$toast.success(response.data.message);
                this.$router.push('/')
            } catch (error) {
                this.$toast.error(error.response ?? error);
            } finally {
                this.$toast.done();
            }
        }
    }
};
</script>
