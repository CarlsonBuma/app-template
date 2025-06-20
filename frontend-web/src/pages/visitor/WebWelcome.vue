<template>

    <PageWrapper showFooter>
        <SectionDesignDefault>
            <SectionSplit>
                <template #left>
                    <SectionTitle 
                        class="text-start w-title"
                        header="Template header."
                        title="Template title."
                        subtitle="Template subtitle"
                    >
                        Lorem ipsum dolor sit amet, consetetur sadipscing elitr, 
                        sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, 
                        sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. 
                        Stet clita kasd gubergren, no sea takimata sa.
                    </SectionTitle>
                </template>
                <template #right>
                    <CardSimple title="Our community" class="w-card-sm text-center">
                        <q-card-section>
                            <q-icon name="diversity_1" size="72px" color="primary" />
                            <p class="q-mt-sm">Some text.</p>
                        </q-card-section>
                    </CardSimple>
                    <CardSimple title="Our Services" class="w-card-sm text-center">
                        <q-card-section>
                            <q-icon name="engineering" size="72px" color="primary" />
                            <p class="q-mt-sm">Some text.</p>
                        </q-card-section>
                    </CardSimple>
                    <CardSimple title="Your Gigs &amp; Support" class="w-card-sm text-center">
                        <q-card-section>
                            <q-icon name="loyalty" size="72px" color="primary" />
                            <p class="q-mt-sm">Some text.</p>
                        </q-card-section>
                    </CardSimple>
                </template>
            </SectionSplit>
        </SectionDesignDefault>
     </PageWrapper>

</template>

<script>
export default {
    name: 'LandingPage',
    components: {
        // Components
    },

    setup() {
        return {
            // 
        }
    },

    data() {
        return {
            // Code
        }
    },

    mounted() {
        // Code
    },

    methods: {
        // Code
    }
};
</script>
