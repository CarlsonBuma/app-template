<style lang="sass">
.my-account-menu-width
  width: 360px
.settings-menu-width
  width: 220px
</style>

<template>

    <q-toolbar class="">
                
        <!-- Logo -->
        <q-toolbar-title class="flex no-wrap items-center">
            <q-avatar @click="$emit('logoClick')" icon="gamepad" size="lg" clickable />
            <q-btn-dropdown
                class="transparent text-subtitle1"
                size="md"
                :label="$env.APP_NAME"
                flat
            >
                <q-list>
                    <q-item clickable v-close-popup @click="$router.push('/landingpage')">
                        <q-item-section>
                            <span class="q-ml-xs cursor-pointer">Home</span>
                        </q-item-section>
                    </q-item>
                    <q-item clickable v-close-popup @click="$router.push('/newsfeed')">
                        <q-item-section>
                            <span class="q-ml-xs cursor-pointer">Newsfeed</span>
                        </q-item-section>
                    </q-item>
                    <q-item clickable v-close-popup @click="$router.push('/pricing')">
                        <q-item-section>
                            <span class="q-ml-xs cursor-pointer">Services &amp; Pricing</span>
                        </q-item-section>
                    </q-item>
                    <q-item clickable v-close-popup @click="$router.push('/about')">
                        <q-item-section>
                            <span class="q-ml-xs cursor-pointer">About us</span>
                        </q-item-section>
                    </q-item>
                </q-list>
            </q-btn-dropdown>
        </q-toolbar-title>

        <!-- App Settings -->
        <q-btn flat size="12px" icon="settings">
            <q-menu class="settings-menu-width">
                <q-list separator>
                    <q-item>
                        <q-item-section>
                            <q-item-label overline>App Preferences</q-item-label>
                        </q-item-section>
                    </q-item>
                    <q-item>
                        <q-select 
                            class="w-100" 
                            label="Language" 
                            v-model="$tp.client_preferences.value.language" 
                            :options="$tp.client_options.lang" 
                            @update:model-value="(value) => $tp.set_cookie('client_language', value)"
                        />
                    </q-item>
                    <q-item>
                        <q-select 
                            class="w-100" 
                            label="Date format" 
                            v-model="$tp.client_preferences.value.dateFormat" 
                            :options="$tp.client_options.date" 
                            @update:model-value="(value) => $tp.set_cookie('client_dateformat', value)"
                        />
                    </q-item>
                    <q-item>
                        <q-toggle 
                            label="Darkmode"
                            :model-value="$tp.client_preferences.value.darkmode"
                            @update:model-value="(value) => $tp.set_darkmode(value)"
                        />
                    </q-item>
                    <q-item>
                        <q-item-section>
                            <q-item-label caption>
                                <b>Note:</b> Preferences are stored via client cookies.
                                <a href="#" @click.prevent="this.$tp.remove_cookies()">Reset cookies</a>.
                            </q-item-label>
                        </q-item-section>
                    </q-item>
                </q-list>
            </q-menu>
        </q-btn>

        <!-- Cockpit -->
        <q-btn 
            flat
            icon="groups_3"
            v-if="$user.access.tokens[$env.APP_ACCESS_COCKPIT]" 
            @click="$router.push('/cockpit/dashboard')"
        />

        <!-- Authorization -->
        <div v-if="!$user.access.user">
            <q-btn
                :disable="loading"
                @click="goMemberArea()" 
                flat 
                size="sm"
                icon="person" 
                label="Member Area"
            />
        </div>

        <!-- User Navigation -->
        <div v-else>

            <!-- Small Screen -->
            <q-fab class="lt-sm" padding="sm" color="white" outline glossy square vertical-actions-align="right" icon="apps" direction="down">
                <q-fab-action 
                    color="primary" square glossy text-color="white"
                    @click="$router.push('/user/dashboard')" 
                    icon="contacts" 
                    label="My Avatar" 
                />
                <q-fab-action 
                    v-if="$user.access.tokens[$env.APP_ACCESS_COCKPIT]"
                    color="primary" square glossy text-color="white" 
                    @click="$router.push('/cockpit/dashboard')" 
                    icon="groups_3" 
                    label="My cockpit" 
                />
                <q-fab-action 
                    color="primary" square glossy text-color="white"
                    @click="$emit('logoutUser')" 
                    icon="logout" 
                    label="Logout" 
                />
                <q-fab-action 
                    v-if="$user.access.tokens[$env.APP_ACCESS_ADMIN]" 
                    color="primary" 
                    square glossy text-color="white" 
                    @click="$router.push('/admin/dashboard')" 
                    icon="admin_panel_settings" 
                    label="Backpanel" 
                />
            </q-fab>
            
            <!-- Big Screen -->
            <div class="gt-xs flex justify-end">

                <!-- Account -->
                <q-separator vertical color="white" class="q-mt-md q-mb-md q-ml-sm q-mr-sm" />
                <q-item clickable v-ripple >
                    <q-item-section>My Account</q-item-section>
                    <q-menu class="my-account-menu-width">
                        <div class="row no-wrap q-pa-md">

                            <!-- User Profile -->
                            <div class="col-auto text-center">
                                <q-avatar 
                                    v-if="$user.user.avatar_src"
                                    size="72px"
                                    color="primary" 
                                    text-color="white">
                                    <img :src="$user.user.avatar_src">
                                </q-avatar>
                                <q-avatar 
                                    v-else
                                    size="72px"
                                    color="primary" 
                                    text-color="white">U
                                </q-avatar>
                                <div class="text-subtitle2 q-mt-sm q-mb-xs">{{ $user.user.name }}</div>
                                <div class="w-100">
                                    <q-btn
                                        @click="$emit('logoutUser')"
                                        color="primary"
                                        label="Logout"
                                        push
                                        size="sm"
                                        v-close-popup
                                    />
                                </div>
                            </div>

                            <!-- Profile Settings -->
                            <q-separator vertical inset class="q-mx-lg" />
                            <div class="col-grow">
                                <span class="text-overline">My Account</span>
                                <q-separator class="w-100"/>
                                <q-list padding >

                                    <!-- Feature 1: Cockpit -->
                                    <q-item 
                                        v-if="$user.access.tokens[$env.APP_ACCESS_COCKPIT]" 
                                        @click="$router.push('/cockpit/dashboard')" 
                                        clickable v-ripple 
                                    >
                                        <q-item-section avatar>
                                            <q-icon name="groups_3" class="q-mr-sm" />
                                        </q-item-section>
                                        <q-item-section>
                                            <q-item-label>My cockpit</q-item-label>
                                        </q-item-section>
                                    </q-item>

                                    <!-- Avatar -->
                                    <q-item @click="$router.push('/user/dashboard')" clickable v-ripple >
                                        <q-item-section avatar>
                                            <q-icon name="contacts" class="q-mr-sm" />
                                        </q-item-section>
                                        <q-item-section>
                                            <q-item-label>My avatar</q-item-label>
                                        </q-item-section>
                                    </q-item>

                                    <!-- Backpanel -->
                                    <q-item 
                                        clickable v-ripple
                                        v-if="$user.access.tokens[$env.APP_ACCESS_ADMIN]" 
                                        @click="$router.push('/admin/dashboard')"
                                    >
                                        <q-item-section avatar>
                                            <q-icon name="admin_panel_settings" class="q-mr-sm" />
                                        </q-item-section>
                                        <q-item-section>
                                            <q-item-label>Backpanel</q-item-label>
                                        </q-item-section>
                                    </q-item>
                                </q-list>
                            </div>
                        </div>
                    </q-menu>
                </q-item>
            </div>
        </div>
    </q-toolbar>

</template>

<script>
export default {
    name: 'NavHead',

    props: {
        loading: Boolean
    },

    emits: [
        'logoClick',
        'authUser',
        'logoutUser',
    ],

    methods: {
        async goMemberArea() {
            if(!this.$user.checkBearerTokenSet()) this.$router.push('/login')
            else this.$emit('authUser')
        }
    }
};
</script>
