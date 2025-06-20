"use strict";

/**
 ** Translation Pack
 *  > Init: "boot/defaults"
 *  > Access: this.$tp
 *
 * ------------------------------------
 * Extend Package accordingly
 * ------------------------------------   
 */

import { ref } from 'vue';
import { Cookies, Dark } from 'quasar'
import dateFormat from './formats/date.js';
import datetimeFormat from './formats/datetime.js';
import datetimeFormats from './formats/index.js';
import languagePack from './lang/index.js';

// Set Cookie 'client_
// Cookie Consent: Required by system
const setCookie = (name, value) => {
    Cookies.set(name, value, {
        secure: true,
        expires: '160'
    })

    return Cookies.get(name) ?? value
}

const setClientLocation = () => {
    Cookies.remove('client_location')
    navigator.geolocation.getCurrentPosition((position) => {
        setCookie('client_location', {
            lat: position.coords.latitude,
            lng: position.coords.longitude,
            radius: 2097152,
            zoom: 2,
            diameter: 100
        });
    })
}

const removeSystemCookies = () => {
    Cookies.remove('client_dateformat')
    Cookies.remove('client_language')
    Cookies.remove('client_darkmode')
    setClientLocation();
}

export default () => {

    // Client provides translation options
    const clientTranslationOptions = {
        'date': ['international', 'eu', 'us'] ,
        'lang': ['de', 'en']
    };

    // User can choose translation settings
    // Cookie Consent: We store preferences as client cookies
    const clientTranslationPreferences = ref({
        location: Cookies.get('client_location') ?? null,
        dateFormat: Cookies.get('client_dateformat') ?? 'eu',        
        language: Cookies.get('client_language') ?? 'en', 
        darkmode: Cookies.get('client_darkmode') === 'true'
    });

    // Set environment
    Dark.set(clientTranslationPreferences.value.darkmode);

    // Define Translation Package
    return {

        // System preferences
        'get_cookie': (name) => Cookies.get(name),
        'set_cookie': (name, value) => setCookie(name, value),
        'set_client_location': () => setClientLocation(),
        'remove_cookies': () => removeSystemCookies(),

        // Design
        'set_darkmode': (value) => {
            clientTranslationPreferences.value.darkmode = value;
            Dark.set(value);
            setCookie('client_darkmode', value)
        },

        // Options
        'client_options': clientTranslationOptions,
        'client_preferences': clientTranslationPreferences,
        
        // Formatting
        'date': (rawDate) => rawDate && dateFormat[clientTranslationPreferences.value.dateFormat]
            ? dateFormat[clientTranslationPreferences.value.dateFormat](rawDate) 
            : null,
        'datetime': (rawDate) => rawDate && datetimeFormat[clientTranslationPreferences.value.dateFormat]
            ? datetimeFormat[clientTranslationPreferences.value.dateFormat](rawDate) 
            : null,
        'formats': (attribute) => attribute && datetimeFormats[clientTranslationPreferences.value.dateFormat]
            ? datetimeFormats[clientTranslationPreferences.value.dateFormat][attribute]
            : null,
        'lang': (key) => key && languagePack[clientTranslationPreferences.value.language] && languagePack[clientTranslationPreferences.value.language][key]
            ? languagePack[clientTranslationPreferences.value.language][key]
            : null,
    }
}