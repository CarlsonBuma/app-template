<template>

    <PageWrapper>
        <CardSimple 
            class="w-card" 
            title="Email Verification"
            note="*We send the verification token to the provided email. After successful verification you are able to login."
        >
            <q-card-section>
                <FormWrapper
                    buttonText="Send Token"
                    buttonIcon="send"
                    @submit="sendEmailVerification(email)"
                >
                    <q-input
                        filled
                        type="email"
                        v-model="email"
                        label="Enter your email"
                        readonly
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

export default {
    name: 'EmailVerificationRequest',
    components: {
        // 
    },
    
    data() {
        return {
            email: this.$route.params.email,
        };
    },

    methods: {
        async sendEmailVerification(email) {
            try {
                this.$toast.load();
                const response = await this.$axios.post("/email-verification-request", {
                    'email': email,
                });
                this.message = this.$toast.success(response.data.message);
                this.$router.push('/')
            } catch (error) {
                this.message = this.$toast.error(error.response ?? error);
            } finally {
                this.$toast.done();
            }
        }
    }
};
</script>
