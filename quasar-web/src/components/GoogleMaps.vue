<style lang="sass" scoped>
#map-div
    height: 460px
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

    <div class="row">
        <div class="col-12 q-pa-md">
            <q-slider
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
        <div class="col-12">
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
        </div>

        <!-- Geolocation -->
        <div class="col-12 text-center">
            <span class="text-caption text-grey">
                {{ computedMapSearchDistance }}, Lat: {{ latitude }}, Lng: {{ longitude }} 
            </span>
        </div>
    </div>

</template>

<script>
import { ref, computed } from 'vue';
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

    setup(props, context) {
        const googleAPIKey = process.env.APP_GOOGLE_API_KEY ?? '';
        const mapRef = ref(null);
        const mapSearchDiameter = ref(50);
        const mapSearchRadius = ref(4096)      // [m]
        const mapDefaultZoom = ref(10)
        const mapZoomLevel = ref(10)
        const initialLatitude = ref(0)
        const initialLongitude = ref(0)
        const latitude = ref(0)
        const longitude = ref(0)
        const allowGeolocation = ref(false)

        // Get Client Location
        navigator.geolocation.getCurrentPosition((position) => {
            initialLatitude.value = position.coords.latitude
            initialLongitude.value = position.coords.longitude
            latitude.value = position.coords.latitude
            longitude.value = position.coords.longitude
            context.emit('update', mapSearchRadius.value, latitude.value, longitude.value)
        })

        // Calcultate Distance
        const computedMapSearchDistance = computed(() => {
            return mapSearchRadius.value > 1000 
                ? 'r ≈ ' + Math.round(mapSearchRadius.value / 1000 * 10) / 10 + 'km'     // [m] -> [km]
                : 'r ≈ ' + mapSearchRadius.value + 'm'                                  
        })

        const setGeolocationStats = () => {
            initialLatitude.value = latitude.value;
            initialLongitude.value = longitude.value;
        }

        const calcGeolocationSearchRadius = (factor) => {
            // Factor allows to include dynamic searchDiamater (0 - 100, Default = 50)
            // Each zoom is defaultDistance ^ zoomLevel
            // Default distance is at zoomLevel = 1, 50% MapDiameter
            // In here we can specify MapDiamater as current Radius to search within (Accurate!)
            // mapSearchRadius will be in km
            const minZoomDistance = 2;  
            const maxZoomLevels = 22;       // fully zoomed in
            mapZoomLevel.value = mapRef.value?.map?.data?.map?.zoom ?? 10;
            const RadiusMeter = (minZoomDistance ** (maxZoomLevels - mapZoomLevel.value)) * (factor * 2 / 100);
 
            
            // Radius [km]
            mapSearchRadius.value = Math.round(RadiusMeter * 10) / 10;
            context.emit('update', mapSearchRadius.value, latitude.value, longitude.value)
        }

        const updateLocationCenter = () => {
            const center = mapRef.value?.map?.data?.map?.center;
            latitude.value = center ? center.lat() : 0;
            longitude.value = center ? center.lng() : 0;
            context.emit('update', mapSearchRadius.value, latitude.value, longitude.value)
        }

        return {
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
            allowGeolocation,
            computedMapSearchDistance,
            setGeolocationStats,
            calcGeolocationSearchRadius,
            updateLocationCenter
        };
    },

    data() {
        return {
            //
        }
    },

    mounted() {
      this.updateLocationCenter()
    },

    methods: {
        
    }
}
</script>
