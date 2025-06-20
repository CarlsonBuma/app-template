<style lang="sass" scoped>
#map-div
    height: 420px
    width: 520px
#map-circle-geolocation
    position: absolute
    border-radius: 50%
    border: 2px solid $primary
    background-color: rgba($primary, 0.2)
#map-cirlcle-geolocation-dot
    position: absolute
    top: 50%
    left: 50%
    transform: translate(-50%, -50%)
    width: 7px
    height: 7px
    border-radius: 50%
    border: 1px solid $primary
    background-color: rgba($primary, 0.7)
</style>

<template>

    <div class="row w-100" >
        <div class="w-100 q-px-xl q-pt-sm">
            <q-slider
                dense
                switch-label-side
                v-model="mapSearchDiameter"
                :min="1"
                :step="1"
                :label="mapSearchRadius ? true : false"
                :label-value="computedMapSearchDistance"
                @update:model-value="calcGeolocationSearchRadius(mapSearchDiameter)"
            />
        </div>
        <q-separator />
        
        <!-- Googlemaps -->
        <GoogleMap
            id="map-div"
            ref="mapRef"
            :zoom="mapZoomLevel"
            :api-key="googleAPIKey" 
            :center="{
                lat: initialLatitude ? initialLatitude : 0, 
                lng: initialLongitude ? initialLongitude : 0
            }"
            @center_changed="updateLocationCenter()"
            @zoom_changed="calcGeolocationSearchRadius(mapSearchDiameter)"
        >
            <CustomMarker 
                :options="{ 
                    position: {
                        lat: latitude, 
                        lng: longitude
                    }, 
                    anchorPoint: 'BOTTOM_CENTER',
                
                streetViewControl: false,
            }">
                <!-- CircleDiameter -->
                <div
                    id="map-circle-geolocation"
                    :style="{
                        top: -mapSearchDiameter + 'px',
                        left: -mapSearchDiameter + 'px',
                        width: mapSearchDiameter * 2 + 'px',
                        height: mapSearchDiameter * 2 + 'px',
                    }"
                >
                    <div id="map-cirlcle-geolocation-dot"/>
                </div>
            </CustomMarker>
        </GoogleMap>

        <!-- Geolocation -->
        <!-- <div class="col-12 text-center">
            <span class="text-caption text-grey">
              {{ computedMapSearchDistance }}, Lat: {{ latitude }}, Lng: {{ longitude }}  
            </span>
        </div>-->
    </div>

</template>

<script>
import { ref, computed, getCurrentInstance } from 'vue';
import { GoogleMap, CustomMarker } from "vue3-google-map";

export default {
    name: 'GoogleMaps',
    components: {
        // https://github.com/inocan-group/vue3-google-map
        GoogleMap, CustomMarker
    },

    props: {
        title: String
    },  

    emits: [
        'update'
    ],

    setup() {
        const { proxy } = getCurrentInstance();
        const googleAPIKey = process.env.APP_GOOGLE_WEB_KEY ?? '';
        const appLocation = proxy.$tp.get_cookie('client_location') || {};
        
        const mapRef = ref(null);
        const mapSearchDiameter = ref(appLocation.diameter || 100);
        const mapSearchRadius = ref(appLocation.radius || 2097152)      // [m]
        const mapDefaultZoom = ref(appLocation.zoom || 2)
        const mapZoomLevel = ref(appLocation.zoom || 2)
        
        const initialLatitude = ref(appLocation.lat || 47.0)
        const initialLongitude = ref(appLocation.lng || 8.0)
        const latitude = ref(appLocation.lat || 47.0)
        const longitude = ref(appLocation.lng || 8.0)

        // Update Location Center
        const updateLocationCenter = () => {
            const center = mapRef.value?.map?.data?.map?.center;
            latitude.value = center ? center.lat() : 0;
            longitude.value = center ? center.lng() : 0;

            // Set Location Cookie
            proxy.$tp.set_cookie('client_location', {
                lat: latitude.value,
                lng: longitude.value,
                radius: mapSearchRadius.value,
                zoom: mapZoomLevel.value,
                diameter: mapSearchDiameter.value
            });
        }

        // Calculate Radius
        const calcGeolocationSearchRadius = (factor = 50) => {
            // Factor allows dynamic search diameter (0 - 100, Default = 50)
            const minZoomDistance = 2;
            const maxZoomLevels = 22;  // Fully zoomed in

            mapZoomLevel.value = mapRef.value?.map?.data?.map?.zoom ?? 10;

            // Radius
            const radiusMeters = Math.pow(minZoomDistance, maxZoomLevels - mapZoomLevel.value) * (factor / 50)
            mapSearchRadius.value = Math.round(radiusMeters * 10) / 10

            updateLocationCenter();
        };

        // Calcultate Distance
        const computedMapSearchDistance = computed(() => {
            const radius = mapSearchRadius.value;
            return radius >= 1000 
                ? `r ≈ ${(radius / 1000).toFixed(0)} km`  // Convert meters to km with 1 decimal place
                : `r ≈ ${radius} m`;                     
        });

        return {
            appLocation,
            googleAPIKey,
            mapRef,
            mapSearchDiameter,
            mapSearchRadius,
            mapDefaultZoom,
            mapZoomLevel,
            initialLatitude,
            initialLongitude,
            latitude,
            longitude,
            computedMapSearchDistance,
            calcGeolocationSearchRadius,
            updateLocationCenter,
        };
    },

    data() {
        return {
            //
        }
    },

    mounted() {
        this.initAppLocation()
    },

    methods: {
        initAppLocation() {

            // Check cookie Set
            if(this.appLocation?.lng && this.appLocation?.lat) {
                this.initialLatitude = this.appLocation.lat
                this.initialLongitude = this.appLocation.lng
                this.latitude = this.appLocation.lat
                this.longitude = this.appLocation.lng
                this.mapSearchRadius = this.appLocation.radius
                this.mapZoomLevel = this.appLocation.zoom
                this.mapSearchDiameter = this.appLocation.diameter
            } 

            // No Cookie Set - Initial location
            else {
                navigator.geolocation.getCurrentPosition((position) => {
                    this.initialLatitude = position.coords.latitude
                    this.initialLongitude = position.coords.longitude
                    this.latitude = position.coords.latitude
                    this.longitude = position.coords.longitude
                })
            }
        }
    }
}
</script>
