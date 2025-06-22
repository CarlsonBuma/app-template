<template>

    <PageWrapper>

        <!-- Header -->
        <template #header>
            <q-tabs v-model="tab" >
                <q-tab name="settings" label="Account Settings" />
            </q-tabs>
            <q-separator class="w-100" />
        </template>
        
        <div class="row w-100 flex justify-center">
            <div class="w-card-sm">

                <!-- Avatar -->
                <ImageUpload 
                    :ratio="1" 
                    :url="$user.user.avatar_src"
                    @submit="(src) => saveAvatar(src)"
                />

                <!-- Credentials -->
                <CardSimple>
                    <q-card-section>
                        <span class="text-caption"><b>ID:</b>&nbsp;#{{ $user.user.id }}</span><br>
                        <span class="text-caption"><b>Username:</b>&nbsp;{{ $user.user.name }}</span><br>
                        <span class="text-caption"><b>Email:</b>&nbsp;{{ $user.user.email }}</span>
                    </q-card-section>
                </CardSimple>
            </div>

            <div class="w-avatar">
                <!-- Username -->
                <CardSimple title="Change username">
                    <template #actions>
                        <q-btn 
                            label="Update"
                            outline
                            color="primary"
                            size="sm"
                            @click="submitUsername()"
                        />
                    </template>
                    <q-card-section >
                        <q-input v-model="$user.user.name" />
                    </q-card-section>
                </CardSimple> 

                <!-- Password -->
                <CardSimple 
                    title="Reset password" 
                    note="You will receive an email containing a token to reset your password."
                >
                    <template #actions>
                        <q-btn 
                            label="Send Token"
                            outline
                            color="primary"
                            size="sm"
                            @click="resetPasswordConfirmation()"
                        />
                    </template>
                </CardSimple> 

                <!-- Invalidate -->
                <CardSimple 
                    title="Invalidate Tokens" 
                    note="Selecting this option will remove access from all devices and log you out."
                >
                    <template #actions>
                        <q-btn 
                            label="Invalidate"
                            outline
                            color="orange"
                            size="sm"
                            @click="invalidateTokenConfirmation()"
                        />
                    </template>
                </CardSimple> 

                <!-- Invalidate -->
                <CardSimple 
                    title="Invalidate Email" 
                    note="This option is recommended only if you wish to connect your account with one of our login providers."
                >
                    <template #actions>
                        <q-btn 
                            label="Invalidate"
                            outline
                            color="orange"
                            size="sm"
                            @click="invalidateEmailConfirmation()"
                        />
                    </template>
                </CardSimple> 
            </div>

            <!-- Account Managment -->
            <div class="w-avatar">
                <!-- Transfer Account -->
                <CardSimple 
                    title="Transfer account" 
                    tooltipColor="orange"
                    note="Update the email address associated with your account. The new email must be verified by its owner to complete the update."
                >
                    <template #actions>
                        <q-btn 
                            label="Change owner"
                            outline
                            color="red"
                            size="sm"
                            @click="transferAccountConfirm(transferEmail, emailPassword)"
                        />
                    </template>

                    <q-card-section>
                        <q-input
                            disable
                            type="email"
                            v-model="$user.user.email"
                            label="Current owner"
                        />
                        <q-input
                            type="email"
                            v-model="transferEmail"
                            label="Transfer account to"
                            placeholder="Enter email"
                        />
                        <q-input
                            type="password"
                            v-model="emailPassword"
                            label="Confirm by password"
                        />
                    </q-card-section>
                </CardSimple>

                <!-- Delete Account -->
                <CardSimple 
                    title="Delete account"
                    note="Once the account is deleted, all your personal data and any associated information will be permanently removed from our system."
                >
                    <template #actions>
                        <q-btn 
                            label="Delete Account"
                            outline
                            color="red"
                            size="sm"
                            @click="deleteAccountConfirm(deletePassword)"
                        />
                    </template>
                    <q-card-section >
                        <q-input type="password" label="Confirm by password" v-model="deletePassword" />
                    </q-card-section>
                </CardSimple>
            </div>
        </div>
    </PageWrapper>

</template>

<script>
import ImageUpload from 'components/ImageUpload.vue';

export default {
    name: 'UserAccountSettings',
    components: {
        ImageUpload
    },
    
    emits: [
        'removeSession'
    ],

    data() {
        return {
            tab: 'settings',

            password: {
                current: '',
                new: '',
                confirm: ''
            },

            emailPassword: '',
            transferEmail: '',
            deletePassword: ''
        };
    },

    methods: {

        async saveAvatar(image) {
            try {
                const formData = new FormData;
                if(image.file) formData.append("file", image.file);
                this.$toast.load();
                const response = await this.$axios.post('/user-update-avatar', formData);
                this.$toast.success(response.data.message);
                this.$user.user.avatar_src = image.img_src;
            } catch (error) {
                this.$toast.error(error.response ?? error);
            }
        },

        async submitUsername() {
            try {
                if(this.$user.user.name.length === 0) throw ('Please enter name.');
                this.$toast.load();
                const response = await this.$axios.post('/user-update-name', {
                    name: this.$user.user.name
                });
                this.$toast.success(response.data.message);
            } catch (error) {
                this.$toast.error(error.response ?? error);
            }
        },

        async resetPassword() {
            try {
                this.$toast.load();
                const response = await this.$axios.post('user-reset-password');
                this.$toast.success(response.data.message)
                this.$emit('removeSession');
            } catch (error) {
                this.$toast.error(error.response ?? error);
            } finally {
                this.password.current = '';
                this.password.new = '';
                this.password.confirm = '';
            }
        },

        resetPasswordConfirmation() {
            this.$q.dialog({
                title: 'Reset Your Password',
                message: 'An email containing a reset token will be sent to your registered email address. Please use it to securely reset your password.',
                cancel: true,
            }).onOk(() => {
                this.resetPassword()
            })
        },

        async invalidateTokens() {
            try {
                this.$toast.load();
                const response = await this.$axios.post('user-invalidate-tokens');
                this.$toast.success(response.data.message)
                this.$emit('removeSession');
            } catch (error) {
                this.$toast.error(error.response ?? error);
            } finally {
                this.password.current = '';
                this.password.new = '';
                this.password.confirm = '';
            }
        },

        invalidateTokenConfirmation() {
            this.$q.dialog({
                title: 'Invalidation your Tokens',
                message: 'This action will remove access from all your devices. Are you sure you want to proceed?',
                cancel: true,
            }).onOk(() => {
                this.invalidateTokens()
            })
        },

        async invalidateEmail() {
            try {
                this.$toast.load();
                const response = await this.$axios.post('user-invalidate-email');
                this.$toast.success(response.data.message)
                this.$emit('removeSession');
            } catch (error) {
                this.$toast.error(error.response ?? error);
            } finally {
                this.password.current = '';
                this.password.new = '';
                this.password.confirm = '';
            }
        },

        invalidateEmailConfirmation() {
            this.$q.dialog({
                title: 'Email Invalidation',
                message: 'You will need to verify your account again, either using the token sent to your email or through one of our login providers.',
                cancel: true,
            }).onOk(() => {
                this.invalidateEmail()
            })
        },

        async transferAccount(transferMail, password) {
            try {
                if(!transferMail) throw 'Email field is required.';
                if(!password) throw 'Please cofirm by password.';
                this.$toast.load();
                const response = await this.$axios.post('user-transfer-account', {
                    'email': transferMail,
                    'password': password,
                })
                this.$toast.success(response.data.message);
                // this.$emit('removeSession');
            } catch (error) {
                this.$toast.error(error.response ?? error);
            } finally {
                this.emailPassword = '';
            }
        },

        transferAccountConfirm(transferMail, password) {
            this.$q.dialog({
                title: 'Transfer Account to New Owner',
                message: 'A token will be sent to the new owner for validation. You can continue using your account with your current credentials until the new owner verifies the transfer.',
                cancel: true,
            }).onOk(() => {
                this.transferAccount(transferMail, password)
            })
        },
        
        async deleteAccount(deletePassword) {
            try {
                if(!deletePassword) throw 'Please confirm by your password.'
                this.$toast.load();
                const response = await this.$axios.post('user-delete-account', {
                    'password': deletePassword,
                });
                this.$toast.success(response.data.message);
                this.$emit('removeSession');
            } catch (error) {
                this.$toast.error(error.response ?? error);
            } finally {
                this.deletePassword = '';
            }
        },

        deleteAccountConfirm(deletePassword) {
            this.$q.dialog({
                title: 'Delete Account',
                message: 'Are you sure you want to delete your account? This action is irreversible, and all your data will be permanently removed.',
                cancel: true,
            }).onOk(() => {
                this.deleteAccount(deletePassword)
            })
        }
    }
};
</script>
