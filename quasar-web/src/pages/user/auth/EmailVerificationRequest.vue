<template>

    <PageWrapper>
        <CardWrapper
            class="w-card-lg"
            title="Email verification"
            iconHeader="verified"
            note="*We send the verification token to the provided email. After successful verification you are able to login."
            goBack
        >
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
        </CardWrapper>
    </PageWrapper>

</template>

<script>
import CardWrapper from 'components/CardWrapper.vue';

export default {
    name: 'EmailVerificationRequest',
    components: {
        CardWrapper
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
