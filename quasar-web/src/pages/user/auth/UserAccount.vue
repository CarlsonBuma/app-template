<template>

    <PageWrapper>
        <template #navigation>
            <NavUser />
        </template>

        <div class="row w-100 flex justify-center">
            <div class="w-avatar">

                <!-- Avatar -->
                <CardUploadImage 
                    allowUpdate
                    :userAvatar="$user.user.avatar_src"
                    @update="(src, avatar, deleteAvatar) => saveAvatar(src, avatar, deleteAvatar)"
                    @upload="(src, avatar) => {
                        $user.user.avatar_src = avatar;
                    }"
                />

                <!-- User -->
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
                    <q-card-section >
                        <FormWrapper
                            buttonText="Change name"
                            buttonIcon="person"
                            @submit="submitUsername()"
                        >
                            <q-input v-model="$user.user.name" label="Username" />
                        </FormWrapper>
                    </q-card-section>
                </CardSimple> 

                <!-- Password -->
                <CardSimple title="Change password">
                    <q-card-section >
                        <FormWrapper
                            buttonText="Change password"
                            buttonIcon="lock"
                            @submit="submitPassword(password.current, password.new, password.confirm)"
                        >
                            <q-input
                                type="password"
                                v-model="password.current"
                                label="Confirm current password"
                            />
                            <div>
                                <q-input type="password" v-model="password.new" label="Enter new password" >
                                    <!-- Validation -->
                                    <template v-slot:append>
                                        <q-icon name="info">
                                            <q-tooltip>
                                                <PasswordCheck
                                                    :password="password.new"
                                                    :password_confirm="password.confirm"
                                                />
                                            </q-tooltip>
                                        </q-icon>
                                    </template>
                                </q-input>
                                <q-input type="password" label="Confirm new password" v-model="password.confirm"/>
                            </div>
                        </FormWrapper>
                    </q-card-section>
                </CardSimple> 
            </div>

            <!-- Transfer Account -->
            <div class="w-avatar">
                <CardSimple 
                    title="Transfer account" 
                    tooltip="Update your account email address. The new email must be verified by its owner to complete the change."
                    tooltipIconColor="orange"
                    note="To cancel the transfer process, log in with your previous credentials and follow the provided steps."
                >
                    <q-card-section>
                        <FormWrapper
                            buttonText="Change owner"
                            buttonIcon="people_alt"
                            @submit="submitEmail(this.transferEmail, this.emailPassword)"
                        >
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
                        </FormWrapper>
                    </q-card-section>
                </CardSimple>

                <!-- Delete Account -->
                <CardSimple 
                    title="Delete account"
                    note="Account restoration is not possible. Your account will be permanently deleted."
                    tooltip="Once the account is deleted, all your personal data and any associated information will be permanently removed from our system."
                >
                    <q-card-section >
                        <FormWrapper
                            buttonText="Delete account"
                            buttonIcon="delete"
                            buttonColor="red"
                            @submit="deleteAccountConfirm()"
                        >
                            <q-input type="password" label="Confirm by password" v-model="deletePassword" />
                        </FormWrapper>
                    </q-card-section>
                </CardSimple>
            </div>
        </div>
    </PageWrapper>

</template>

<script>
import PasswordCheck from 'components/PasswordCheck.vue';
import CardUploadImage from 'components/CardUploadImage.vue';

export default {
    name: 'UserAccountSettings',
    components: {
        PasswordCheck, CardUploadImage
    },
    
    emits: [
        'removeSession'
    ],

    data() {
        return {
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

        async saveAvatar(src, avatar, deleteAvatar) {
            if(!src && !deleteAvatar ) return;
            try {
                const formData = new FormData;
                if(src) formData.append("avatar", src);
                formData.append("avatar_delete", deleteAvatar ? '1' : '0');
                this.$toast.load();
                const response = await this.$axios.post('/update-user-avatar', formData);
                this.$toast.success(response.data.message);
                this.$user.user.avatar_src = avatar;
            } catch (error) {
                this.$toast.error(error.response ?? error);
            }
        },

        async submitUsername() {
            try {
                if(this.$user.user.name.length === 0) throw ('Please enter name.');
                this.$toast.load();
                const response = await this.$axios.post('/update-user-name', {
                    name: this.$user.user.name
                });
                this.$toast.success(response.data.message);
            } catch (error) {
                this.$toast.error(error.response ?? error);
            }
        },

        async submitPassword(current, newPw, confirmed) {
            try {
                if(!current) throw 'Please enter new password.';
                const passwordCheck = this.$globals.checkPasswordRequirements(newPw, confirmed);
                if(passwordCheck) throw passwordCheck;
                
                // Request
                this.$toast.load();
                const response = await this.$axios.post('update-user-password', {
                    'password_current': current,
                    'password': newPw,
                    'password_confirmation': confirmed
                });
                
                this.$toast.success(response.data.message)
            } catch (error) {
                this.$toast.error(error.response ?? error);
            } finally {
                this.password.current = '';
                this.password.new = '';
                this.password.confirm = '';
            }
        },

        async submitEmail(transferMail, password) {
            try {
                if(!transferMail) throw 'Email field is required.';
                if(!password) throw 'Please cofirm by password.';
                this.$toast.load();
                const response = await this.$axios.post('transfer-user-account', {
                    'email': transferMail,
                    'password': password,
                })
                this.$toast.success(response.data.message);
                this.$emit('removeSession');
            } catch (error) {
                this.$toast.error(error.response ?? error);
            } finally {
                this.emailPassword = '';
            }
        },
        
        async deleteAccount() {
            try {
                if(!this.deletePassword) throw 'Please confirm by your password.'
                this.$toast.load();
                const response = await this.$axios.post('user-delete-account', {
                    'password': this.deletePassword,
                });
                this.$toast.success(response.data.message);
                this.$emit('removeSession');
            } catch (error) {
                this.$toast.error(error.response ?? error);
            } finally {
                this.deletePassword = '';
            }
        },

        deleteAccountConfirm() {
            this.$q.dialog({
                title: 'Confirm delete',
                message: 'Sure you want to delete your account? Your data will be deleted permanently.',
                cancel: true,
            }).onOk(() => {
                this.deleteAccount()
            })
        }
    }
};
</script>
