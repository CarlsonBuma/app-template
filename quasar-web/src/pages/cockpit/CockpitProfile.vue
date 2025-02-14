<template>

    <PageWrapper :rendering="loading" >
        <template #navigation>
            <NavCockpit />
        </template>

        <div class="w-avatar">
            <!-- Make public -->
            <CardSimple 
                title="Join our community" 
                tooltip="No logic defined yet."
                tooltipIconColor="primary"
            >
                <template #actions>
                    <div class="flex justify-end w-100">
                        <q-toggle 
                            v-model="cockpit.is_public"
                            class="q-ml-md q-mr-sm" 
                            dense 
                        />
                        <q-btn 
                            @click="updatePublicity(cockpit.is_public)" 
                            :disabled="!cockpit.name || !cockpit.avatar"
                            outline 
                            rounded
                            size="sm"
                            color="primary" 
                            label="Update" 
                        >
                            <q-tooltip v-if="!cockpit.name || !cockpit.avatar">
                                Cockpit name &amp; avatar are required.
                            </q-tooltip>
                        </q-btn>
                    </div>
                </template>
            </CardSimple>

            <!-- Avatar -->
            <CardUploadImage 
                :userAvatar="cockpit.avatar" 
                :name="cockpit.name" 
                :slogan="cockpit.slogan"
                allowUpdate
                @update="(src, avatar, deleteAvatar) => udpateAvatar(src, deleteAvatar)"
                @upload="(src, avatar) => {
                    cockpit.avatar = avatar;
                }"
            />

            <!-- Name -->
            <CardSimple title="Cockpit name">           
                <q-card-section>
                    <FormWrapper
                        buttonText="Update name"
                        buttonIcon="update"
                        @submit="updateName(cockpit.name)"
                    >
                        <q-input v-model="cockpit.name" label="Cockpit name"/>
                    </FormWrapper>
                </q-card-section>
            </CardSimple>
        </div>

        <div class="w-card-lg">

            <!-- About -->
            <CardSimple title="About us">            
                <q-card-section>
                    <FormWrapper
                        buttonText="Update about"
                        buttonIcon="update"
                        @submit="updateAbout(cockpit.about)"
                    >
                        <q-input
                            class="q-mt-md"
                            label="About us"
                            v-model="cockpit.about"
                            maxlength="999"
                            type="textarea"
                            placeholder="Tell us about your business..."
                            counter
                        />
                    </FormWrapper>
                </q-card-section>
            </CardSimple>

            <!-- Impressum -->
            <CardSimple title="Impressum">        
                <q-card-section>
                    <FormWrapper
                        buttonText="Update Impressum"
                        buttonIcon="update"
                        @submit="updateImpressum(cockpit.website, cockpit.contact)"
                    >
                        <q-input 
                            v-model="cockpit.website" 
                            label="Website" 
                            placeholder="www.website.io"
                        />
                        <q-input
                            v-model="cockpit.contact"
                            maxlength="199"
                            label="Contact details"
                            type="textarea"
                            counter
                            autogrow
                        />
                    </FormWrapper>
                </q-card-section>
            </CardSimple>
        </div>

        <!-- SEO -->
        <div class="w-avatar">
            <!-- Geolocation -->
            <CardSimple v-if="cockpit.location" title="Location">
                <q-card-section>
                    <FormWrapper
                        buttonText="Update Location"
                        buttonIcon="update"
                        @submit="updateLocation(cockpit.location)"
                    >
                        <GoogleLocation v-model="cockpit.location" />
                    </FormWrapper>
                </q-card-section>
            </CardSimple>

            <!-- Bulletpoints -->
            <CardSimple title="Bulletpoints">            
                <q-card-section>
                    <FormWrapper
                        buttonText="Update bulletpoints"
                        buttonIcon="update"
                        @submit="updateBulletpoints(cockpit.tags)"
                    >
                        <q-select
                            label="Enter bulletpoints..."
                            v-model="cockpit.tags"
                            use-input
                            use-chips
                            multiple
                            max-values="9"
                            counter
                            hide-dropdown-icon
                            input-debounce="0"
                            new-value-mode="add-unique"
                        />
                    </FormWrapper>
                </q-card-section>
            </CardSimple>
        </div>
    </PageWrapper>

</template>

<script>
import { ref } from 'vue'
import { regRules } from 'src/boot/modules/globals';
import NavCockpit from 'src/components/navigation/NavCockpit.vue';
import FormWrapper from 'src/components/global/FormWrapper.vue';
import GoogleLocation from 'src/components/GoogleLocation.vue';
import CardUploadImage from 'components/CardUploadImage.vue';

export default {
    name: 'CockpitProfile',
    components: {
        NavCockpit, FormWrapper, GoogleLocation, CardUploadImage
    },

    setup() {
        return {
            loading: ref(true),
            regRules
        };
    },

    data() {
        return {
            cockpit: {
                location: {}
            }
        }
    },

    mounted() {
        this.loadAttributes()
    },

    methods: {
        async loadAttributes() {
            try {
                this.loading = true;
                const cockpitResponse = await this.$axios.get('/load-cockpit-profile');
                this.cockpit = cockpitResponse.data.cockpit;
            } catch (error) {
                this.$toast.error(error.response ?? error)
            } finally {
                this.loading = false;
            }
        },

        async updatePublicity(cockpit_isPublic) {
            try {
                this.$toast.load();
                const response = await this.$axios.post('/update-cockpit-public-access', {
                    is_public: cockpit_isPublic
                });
                this.$toast.success(response.data.message);
            } catch (error) {
                this.$toast.error(error.response ?? error)
            } finally {
                this.$toast.done();
            }
        },

        async udpateAvatar(src, deleteAvatar) {
            try {
                if(!src && !deleteAvatar) return;
                const formData = new FormData;
                if(src) formData.append("src", src);
                formData.append("avatar_delete", deleteAvatar ? '1' : '0');
                this.$toast.load();
                const response = await this.$axios.post('/update-cockpit-avatar', formData);
                this.$toast.success(response.data.message);
            } catch (error) {
                this.$toast.error(error.response ?? error);
            }
        },

        async updateName(name) {
            try {
                this.$toast.load();
                const response = await this.$axios.post('/update-cockpit-credits', {
                    name: name,
                });
                this.$toast.success(response.data.message);
            } catch (error) {
                this.$toast.error(error.response ?? error);
            }
        },

        async updateAbout(about) {
            try {
                this.$toast.load();
                const response = await this.$axios.post('/update-cockpit-about', {
                    about: about,
                });
                this.$toast.success(response.data.message);
            } catch (error) {
                this.$toast.error(error.response ?? error)
            }
        },

        async updateLocation(location) {
            try {
                if(!location.place_id) throw 'Please enter address.';
                this.$toast.load();
                const response = await this.$axios.post('/update-cockpit-location', {
                    place_id: location.place_id,
                    lng: location.lng,
                    lat: location.lat,
                    address: location.address,
                    country: location.country,
                    country_short: location.country_short,
                    area: location.area,
                    area_short: location.area_short,
                    zip_code: location.zip_code
                });
                this.$toast.success(response.data.message);
            } catch (error) {
                this.$toast.error(error.response ?? error)
            }
        },

        async updateBulletpoints(tags) {
            try {
                this.$toast.load();
                const response = await this.$axios.post('/update-cockpit-bulletpoints', {
                    tags: tags,
                });
                this.$toast.success(response.data.message);
            } catch (error) {
                this.$toast.error(error.response ?? error)
            }
        },

        async updateImpressum(website, contact) {
            try {
                if(!this.regRules.sanitizeLink.test(website)) throw 'Invalid link.'
                this.$toast.load();
                const response = await this.$axios.post('/update-cockpit-impressum', {
                    website: website,
                    contact: contact,
                });
                this.$toast.success(response.data.message);
            } catch (error) {
                this.$toast.error(error.response ?? error)
            }
        },
    },
};
</script>
