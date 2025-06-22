<template>

    <PageWrapper>
        <CardSimple title="Verify Access" class="w-card">
            <q-card-section>
                {{ this.email 
                    ? 'Verification in progress...' 
                    : 'This account is not linked. To sign in with external providers, invalidate your email in account settings and log in with your preferred provider again.' 
                }}
            </q-card-section>
        </CardSimple>
    </PageWrapper>

</template>

<script>
export default {
    name: 'UserAuth',
    components: {
        //
    },

    emits: [
        'setSession'
    ],

    props: {
        email: String,       // URL
        token: String,       // URL
    },

    mounted() { 
        this.$user.removeBearerToken();
        this.authenticate(this.email, this.token)
    },
    
    methods: {
        async authenticate(email, token) {
            try {
                if(!email || !token) return;
                this.$toast.load();
                const response = await this.$axios.post(this.$route.fullPath, {
                    'email': email,
                    'token': token,
                });
                
                // Login
                this.$user.setBearerToken(response.data.token);
                this.$emit('setSession', '/user');
            } catch (error) {
                this.$toast.error(error.response ?? error);
            }
        }
    }
};
</script>
