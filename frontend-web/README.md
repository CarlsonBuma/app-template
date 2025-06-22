# Quasar CLI - Framework
Website: https://quasar.dev/introduction-to-quasar

# Stack Overview
Key features, that enriches our frontend in combination of /backend APIs.
 > Quasar CLI v.2.17 ("$quasar info") 
    > VueJS Framework
 > User Authentication
    > see "\src\pages\auth\"
 > User Access Management
    > see "\src\pages\access\"
    > Note: Client Checkout (by PaddleJS) as initial user-access-request
        > User-access-request must be verified via our backend webhooks
        > see: "User Access Management"
 > Cookie Consent (cookieConsent.js)
    > https://github.com/eyecatchup/vue-cookieconsent
    > see "\boot\cookieConsent"
 > Google API - Geolocation
    > https://developers.google.com/maps/documentation/geocoding/start
    > see "\components\Google..."

## User Account Management
Basic user authentification via _BearerTokens and account access.
    - See "src\pages\user\auth"

## User Access Management
Access to users will be granted manually (eg. via admin) or automatically letting users purchase a Price, which defines access to certain app features. 
    - See "src\pages\user\UserAccess.vue"
    - See "src\pages\admin\AdminAccessManagement"

### Setup: Client Checkout - by PaddleJS
A user can buy access to app features, by purchasing prices. Payments will be handeled within Client Checkout provided by PaddleJS.
    1. Setup Paddle Account
        - https://developer.paddle.com/paddlejs/overview
        - Paddle Cockpit Authentication: Create Client Side Token
            - Set key in .env file
    2. Implement PaddleJS
        - See "src\pages\access\components\PaddlePriceJS.vue"
        - Note: Available prices and its access are provided via app backend
    3. Initialize: Client Checkout
        - See "src\pages\user\UserAccess.vue"
        - Note: Initialize user-access-request after client checkout completed, for further access verification via backend webooks handling.

## App Geolocation
Google Geolocation allows geolocating addresses within our app, provided by Google Developer Services API.
    1. Create Google Developers Account
        - Google Maps: Geolocation
            - https://developers.google.com/maps/documentation/geocoding/start 
        - Google Maps: Map Implementation    
            - https://github.com/inocan-group/vue3-google-map
            - https://developers.google.com/maps/documentation/javascript?hl=de
        - Setup .env File
    3. Initialize Geolocation
        - see "\components\GoogleLocations.vue"
        - Note: Sets address and geolocation of a entity.
    4.  Initialize GoogleMaps
        - see "\components\GoogleMaps.vue"
        - Note: Allows system to retrieve matching entites within specified area by geolocation.

## Cookie-Consent (for Google Analytics, Bing UET, etc.)
GDPR Compliant Cookie Consent, that allows implement optional analytic tools.
    - Setup: see "\boot\modules\cookieConsentOptions.js"
    - Init (this.$cc): see "\boot\default.js" 

## Template Translation Pack
Support different languages, regional formats, and cultural contexts, making the software accessible and user-friendly to diverse global audiences.
    - Setup: see "\boot\translations\"
    - Init (this.$tp): see "\boot\default.js"
