<template>

    <q-toolbar>
                
        <!-- Logo -->
        <q-toolbar-title>
            <q-btn
                flat
                size="md"
                icon="menu_open"
                @click="$emit('logoClick')"
            />
        </q-toolbar-title>

        <!-- App Settings -->
        <q-btn flat round icon="settings" size="sm">
            <q-menu class="w-card-sm">
                <q-list separator>
                    
                    <q-item>
                        <q-item-section>
                            <q-item-label overline>App Preferences</q-item-label>
                        </q-item-section>
                    </q-item>

                    <!--<q-item>
                        <q-select 
                            class="w-100" 
                            label="Language" 
                            v-model="$tp.client_preferences.value.language" 
                            :options="$tp.client_options.lang" 
                            @update:model-value="(value) => $tp.set_cookie('client_language', value)"
                        />
                    </q-item> -->

                    <!-- Dateformat -->
                    <q-item>
                        <q-select 
                            class="w-100" 
                            label="Datetime format" 
                            v-model="$tp.client_preferences.value.dateFormat" 
                            :options="$tp.client_options.date" 
                            @update:model-value="(value) => $tp.set_cookie('client_dateformat', value)"
                        />
                    </q-item>

                    <!-- Geolocation -->
                    <q-item clickable>
                        <q-item-section>
                            <q-item-label caption lines="1">
                                <b>Location:</b><br> 
                                Lat: {{ parseFloat(location?.lat.toFixed(7)) ?? '-' }}<br>
                                Lng: {{ parseFloat(location?.lng.toFixed(7)) ?? '-' }}<br>
                                Radius: {{ location?.radius ?? '-' }}<br>
                            </q-item-label>
                        </q-item-section>
                    </q-item>

                    <!-- Darkmode -->
                    <q-item class="flex justify-start">
                        <q-toggle 
                            dense
                            class="text-caption text-grey"
                            label="Darkmode"
                            :model-value="$tp.client_preferences.value.darkmode"
                            @update:model-value="(value) => $tp.set_darkmode(value)"
                        />
                    </q-item>

                    <!-- Cookies -->
                    <q-item>
                        <q-item-section>
                            <q-item-label caption>
                                <b>Note:</b> Preferences are stored via client cookies.
                                <a href="#" @click.prevent="$tp.remove_cookies()">Reset preferences</a>.
                            </q-item-label>
                        </q-item-section>
                    </q-item>
                </q-list>
            </q-menu>
        </q-btn>

        <!-- Geolocation -->
        <q-separator vertical color="white" class="q-my-md q-mx-sm" />
        <q-btn flat round size="md" icon="share_location">
            <q-menu>
                <keep-alive>
                    <GoogleMaps class="w-card-md" />
                </keep-alive>
            </q-menu>
        </q-btn>
    </q-toolbar>

</template>

<script>
import { computed, getCurrentInstance } from 'vue';
import GoogleMaps from 'components/GoogleMaps.vue';

export default {
    name: 'NavHead',
    components: {
        GoogleMaps
    },

    props: {
        loading: Boolean
    },

    emits: [
        'logoClick',
    ],

    setup() {
        
        // Init Client Location
        const { proxy } = getCurrentInstance();
        // proxy.$tp.set_client_location();

        // Keep client location up-to-date
        const location = computed(() => {
            return proxy.$tp.get_cookie('client_location');
        });

        return {
            location
        };
    },

    mounted() {
        // Code
    },

    methods: {
        //
    }
};
</script>
