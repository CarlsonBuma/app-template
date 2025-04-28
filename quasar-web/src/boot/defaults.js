'use strict';

// Default modules
import { defineBoot } from '#q-app/wrappers'
import { ref } from 'vue';
import ResponseHandler from 'src/boot/modules/responseHandling.js';
import useUserStore from "src/stores/user.js";
import globals from 'src/boot/modules/globals.js';

// Translations - l18n workaround
import initTranslationPackage from './translations/index.js'

// Cookie Consent
import CookieConsent from 'vue-cookieconsent';
import CookieConsentOptions from 'src/boot/modules/cookieConsentOptions.js';
import 'vue-cookieconsent/vendor/cookieconsent.css';

export default defineBoot(({ app, router }) => {
    
    // Env Variables
    app.config.globalProperties.$env = {
        APP_NAME: process.env.APP_NAME,
        APP_BASE_URL: process.env.APP_BASE_URL,
    };
    
    // Defaults
    app.config.globalProperties.$showDrawer = ref(false);
    app.config.globalProperties.$user = useUserStore();
    app.config.globalProperties.$globals = globals;

    // Modules
    app.config.globalProperties.$tp = initTranslationPackage();
    app.config.globalProperties.$toast = new ResponseHandler(router, app);
 
    // Cookie-Consent accessible by this.$cc
    app.use(CookieConsent);
    if(app.config.globalProperties.$cc) {
        app.config.globalProperties.$cc.run(CookieConsentOptions);
    }
});
