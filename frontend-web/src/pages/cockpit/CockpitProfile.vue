<template>

    <PageWrapper :rendering="loading" >

        <!-- Header -->
        <template #header>
            <q-tabs v-model="tab" class="q-pt-lg" >
                <q-tab name="impressum" label="Impressum" />
            </q-tabs>
            <q-separator class="w-100" />
            <!--<div class="row w-100 flex justify-end q-pa-md">
                Nav
            </div>-->
        </template>
        
        <div class="w-card-sm">
            
            <!-- Title -->
            <CardSimple>
                <q-card-section>
                    <q-input label="Name" v-model="cockpit.name" >
                        <template #append >
                            <q-btn 
                                dense
                                outline
                                size="sm"
                                icon="update"
                                color="primary"
                                @click="updateName(cockpit.name)"
                            />
                        </template>
                    </q-input>
                </q-card-section>
            </CardSimple>

            <!-- Avatar -->
            <ImageUpload 
                :ratio="1" 
                :url="cockpit?.logo?.url ?? cockpit?.avatar"
                @submit="(src) => udpateAvatar(src, cockpit?.logo?.id)"
            />
        </div>

        <div class="w-card-lg">

            <!-- About -->
            <CardSimple title="About us">    
                <template #actions>
                    <q-btn 
                        outline
                        size="sm"
                        label="Update" 
                        color="primary" 
                        @click="updateAbout(cockpit.about)" 
                    />
                </template>         
                <q-card-section>
                    <q-input
                        class="q-mt-md"
                        label="About us"
                        v-model="cockpit.about"
                        maxlength="999"
                        type="textarea"
                        placeholder="Tell us about your business..."
                        counter
                    />
                </q-card-section>
            </CardSimple>

            <!-- Impressum -->
            <CardSimple title="Impressum">  
                <template #actions>
                    <q-btn 
                        outline
                        size="sm"
                        label="Update" 
                        color="primary" 
                        @click="updateImpressum(cockpit.website, cockpit.contact)" 
                    />
                </template>         
                <q-card-section>
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
                </q-card-section>
            </CardSimple>

            <!-- Bulletpoints -->
            <CardSimple title="Bulletpoints">  
                <template #actions>
                    <q-btn 
                        outline
                        size="sm"
                        label="Update" 
                        color="primary" 
                        @click="updateBulletpoints(cockpit.tags)" 
                    />
                </template>         
                <q-card-section>
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
                </q-card-section>
            </CardSimple>
        </div>
    </PageWrapper>

</template>

<script>
import { ref } from 'vue'
import ImageUpload from 'components/ImageUpload.vue';

export default {
    name: 'CockpitProfile',
    components: {
        ImageUpload
    },

    setup() {
        return {
            tab: ref('impressum'),
            loading: ref(true)
        };
    },

    data() {
        return {
            cockpit: {},
        }
    },

    mounted() {
        this.loadAttributes()
    },

    methods: {
        async loadAttributes() {
            try {
                this.loading = true;
                const cockpitResponse = await this.$axios.get('/cockpit-load-profile');
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
                const response = await this.$axios.post('/cockpit-update-publicity', {
                    is_public: cockpit_isPublic
                });
                this.$toast.success(response.data.message);
            } catch (error) {
                this.$toast.error(error.response ?? error)
            } finally {
                this.$toast.done();
            }
        },

        async udpateAvatar(src, fileID = null) {

            const formData = new FormData;
            if(fileID) formData.append("storage_id", fileID);
            if(src?.file) formData.append("file", src.file);
            
            try {
                this.$toast.load();
                const response = await this.$axios.post('/cockpit-update-avatar', formData);
                this.$toast.success(response.data.message);
                this.cockpit.logo = response.data.file
            } catch (error) {
                this.$toast.error(error.response ?? error);
            }
        },

        async updateName(name) {
            try {
                this.$toast.load();
                const response = await this.$axios.post('/cockpit-update-credits', {
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
                const response = await this.$axios.post('/cockpit-update-about', {
                    about: about,
                });
                this.$toast.success(response.data.message);
            } catch (error) {
                this.$toast.error(error.response ?? error)
            }
        },

        async updateBulletpoints(tags) {
            try {
                this.$toast.load();
                const response = await this.$axios.post('/cockpit-update-bulletpoints', {
                    tags: tags,
                });
                this.$toast.success(response.data.message);
            } catch (error) {
                this.$toast.error(error.response ?? error)
            }
        },

        async updateImpressum(website, contact) {
            try {
                this.$toast.load();
                const response = await this.$axios.post('/cockpit-update-impressum', {
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
