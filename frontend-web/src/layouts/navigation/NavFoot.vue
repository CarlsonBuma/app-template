<template>

    <div class="fit row wrap justify-center">
        <div class="col-12 col-md-3 q-pa-lg text-center">
            <h6>{{ $env?.APP_NAME }}</h6>
            <div>
                <q-tabs label="about" vertical inline-label align="left">
                    <q-route-tab to="/" label="Home" />
                    <q-route-tab to="/about" label="About us" />
                    
                </q-tabs>
            </div>
        </div>
        <div class="col-12 col-md-3 q-pa-lg text-center">
            <h6>Our Plattform</h6>
            <div>
                <q-tabs label="collaboration" vertical inline-label align="left">
                    <q-route-tab to="/newsfeed" label="Newsfeed" />
                </q-tabs>
            </div>
        </div>
        <div class="col-12 col-md-3 q-pa-lg text-center">
            <h6>Tems-of-Use</h6>
            <div>
                <q-tabs label="legal" vertical inline-label align="left">
                    <q-route-tab to="/legal" label="Legal &amp; Compliance" />
                </q-tabs>
            </div>
        </div>
        <div class="col-12 col-md-3 q-pa-lg text-center">
            <div class="q-pa-md q-mt-xl">
                <q-btn 
                    @click="openURL($globals.redirects.linkLinkedin)"
                    round 
                    color="black" 
                    icon="thumb_up_alt" 
                    class="q-mr-xs"/>
                <q-btn 
                    @click="openURL($globals.redirects.linkYoutube)"
                    round 
                    color="black" 
                    icon="play_circle_filled" 
                    class="q-mr-xs"/>
                <q-btn 
                    @click="openURL('mailto:' + $globals.redirects.emailContact)"
                    round 
                    color="black" 
                    icon="email" 
                    class="q-mr-xs"/>
            </div>
            <p>© {{ $env?.APP_NAME }}, 2025</p>
                
            <!-- Darkmode -->
            <q-item class="flex align-center content-center items-center justify-center" >
                <q-icon 
                    name="dark_mode" 
                    size="22px" 
                />
                <q-toggle 
                    label="Darkmode"
                    :model-value="$tp.client_preferences.value.darkmode"
                    @update:model-value="(value) => $tp.set_darkmode(value)"
                />
            </q-item>
        </div>
    </div>
    
</template>

<script>
import { openURL } from 'quasar';

export default {
    name: 'NavFoot',

    setup () {
        return {
            openURL,
        }
    },

    data() {
        return {
            // Data
        };
    },
};
</script>