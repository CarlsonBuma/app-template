<template>

    <PageWrapper>
        <CardWrapper
            class="w-card-lg"
            title="Reset password"
            iconHeader="lock_clock"
            note="*You will receive an email with the provided token. Please click the link we sent to your email."
            goBack
        >
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
        </CardWrapper>
    </PageWrapper>

</template>

<script>
import CardWrapper from 'components/CardWrapper.vue';

export default {
    name: 'PasswordReset',
    components: {
        CardWrapper
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
