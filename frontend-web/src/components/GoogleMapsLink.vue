<template>

    <div class="">
        
        <!-- Redirect Link -->
        <div class="flex-inline items-center">
            <q-icon name="place" color="primary" size="15px" class="" />
            <span 
                v-if="location.country || location.place_id"
                class="_hover text-right" 
                @click="navigateToExternalSite(googleMapsURL + location.lat + ',' + location.lng + '&query_place_id=' + location.place_id)"
            >
               {{ 
                location.area && location.country
                    ? location.area + ', ' + location.country
                        : location.country
                            ??  location.place_id 
                            ?? 'undefined.'
                }}
            </span>
            <span class="_hover text-right" v-else>undefined.</span>
        </div>

        <!-- Distance -->
        <div class="">
            <span v-if="distance">🧭 {{ distance ?? '0' }}km</span>
        </div>
    </div>
    
</template>

<script>
export default {
    name: 'GoogleMapsLink',
    props: {
        location: Object,
        distance: Number,
    },

    setup() {
        const googleMapsURL = 'https://www.google.com/maps/search/?api=1&query=';
        const navigateToExternalSite = (externalURL) => {
            window.open(externalURL, '_blank');
        }

        return {
            googleMapsURL,
            navigateToExternalSite,
        };
    },
};
</script>
