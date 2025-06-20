<style lang="sass">
.timeline-release > .q-timeline__content
    padding: 0px
</style>

<template>

    <PageWrapper showFooter>
        <SectionDesignDefault>
            <div class="w-content-sm w-100 flex justify-center">

                <!-- Title -->
                <SectionTitle 
                    class="text-center"
                    header="Our latest news, announcements and releases."
                    title="Newsfeed"
                >
                    
                </SectionTitle>
                <q-separator class="w-100 q-mb-lg" />

                <!-- Content - infinite scroll -->
                <InfiniteScroll 
                    class="w-content-xs"
                    :offset="400"
                    :disable="isLastEntry"
                    :loading="rendering"
                    @load="getNewsfeed(newsfeed.length)" 
                >
                    <NoData v-if="newsfeed.length === 0 && !rendering" text="No news announced." />
                    <div
                        v-else 
                        v-for="(entry, index) in newsfeed"
                        :key="index"
                        class="flex w-100"
                        :class="entry.type !== 'Good News' ? 'justify-end' : 'justify-start'"
                    >
                        <CardSimple class="w-card-xl">
                            <q-card-section class="q-py-none">
                                    <!-- All Releases -->
                                    <q-timeline 
                                        :color="entry.type === 'Good News' 
                                            ? 'primary' 
                                            : entry.type === 'Notification'
                                                ? 'orange'
                                                : 'purple'" 
                                        side="right"
                                    >
                                        <q-timeline-entry
                                            class="q-pa-none timeline-release text-start"
                                            :title="entry.title"
                                            :subtitle="entry.type + ', ' + $tp.date(entry.created_at)"
                                            :icon="entry.type === 'Good News' 
                                                ? 'grade' 
                                                : entry.type === 'Notification'
                                                    ? 'notifications'
                                                    : 'emoji_events'"
                                        >
                                            <span class="_text-break q-pb-none">{{ entry.description }}</span>
                                        </q-timeline-entry>
                                        <q-timeline-entry class="" />
                                    </q-timeline>
                            </q-card-section>
                        </CardSimple>
                    </div>
                </InfiniteScroll>
            </div>
        </SectionDesignDefault>
    </PageWrapper>

</template>

<script>
import { defineComponent, ref } from 'vue';
import InfiniteScroll from 'components/InfiniteScroll.vue';

export default defineComponent({
    name: 'WebNewsfeed',
    components: {
        InfiniteScroll
    },

    setup() {
        return {
            rendering: ref(false),
            isLastEntry: ref(false),
        }
    },

    data() {
        return {
            newsfeed: []
        }
    },

    methods: {
        async getNewsfeed(index) {
            try {
                // Limit to last entry
                // Only load if there are more entries existing.
                if(this.rendering === true || this.isLastEntry) return;
                this.isLastEntry = true;
                
                // Request
                this.rendering = true;
                const response = await this.$axios.get("/get-app-newsfeed", { params: { 
                    index: index 
                }});

                // Add newsfeed & check if there are more
                this.newsfeed.push(...response.data.newsfeed);
                this.isLastEntry = response.data.is_last_entry;
            } catch (error) {
                console.log('visitor.newsfeed', error.response ?? error)
                this.isLastEntry = true;
            } finally {
                this.rendering = false;
            }
        },
    }
});
</script>
