<template>

    <PageWrapper noMargin showFooter>
        <SectionDesignDefault>
            <SectionSplit>
                <template #left>
                    <SectionTitle 
                        class="text-start w-title"
                        header="- Start your own amazing app."
                        title="Create something amazing!"
                    >
                        Lorem ipsum dolor sit amet, consetetur sadipscing elitr, 
                        sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, 
                        sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. 
                        Stet clita kasd gubergren, no sea takimata sa.
                    </SectionTitle>
                </template>
                <template #right>
                    <CardSimple title="Our Vision" class="text-center w-card-sm" shadow>
                        <q-card-section>
                            <q-icon name="app_shortcut" size="72px" color="primary" />
                            <p class="q-mt-sm">Some text.</p>
                        </q-card-section>
                    </CardSimple>
                    <CardSimple title="Our Mission" class="text-center w-card-sm" shadow>
                        <q-card-section>
                            <q-icon name="flag_circle" size="72px" color="primary" />
                            <p class="q-mt-sm">Some text.</p>
                        </q-card-section>
                    </CardSimple>
                    <CardSimple title="Our Statement" class="text-center w-card-sm" shadow>
                        <q-card-section>
                            <q-icon name="loyalty" size="72px" color="primary" />
                            <p class="q-mt-sm">Some text.</p>
                        </q-card-section>
                    </CardSimple>
                    <CardSimple title="Our Philosophy" class="text-center w-card-sm" shadow>
                        <q-card-section class="w-100">
                            <q-icon name="tips_and_updates" size="72px" color="primary" />
                            <p class="q-mt-sm">Some text.</p>
                        </q-card-section>
                    </CardSimple>
                </template>
            </SectionSplit>
        </SectionDesignDefault>

        <!-- Our Services -->
        <SectionDesignColored class="text-white">
            <SectionTitle 
                class="text-center"
                title="Our community."
            >
                Lorem ipsum dolor sit amet, consetetur sadipscing elitr, 
                sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, 
                sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. 
                Stet clita kasd gubergren, no sea takimata sa.
            </SectionTitle>

            <div class="w-content-sm q-pt-md text-left">
                <!-- Step 1 -->
                <div class="row q-mb-md">
                    <div class="col-12 col-sm-6 flex justify-end items-center q-pa-md">
                        <q-icon name="contacts" size="160px" color="white"/>
                    </div>
                    <div class="col-12 col-sm-6 flex justify-start items-center q-pa-md">
                        <div class="text-start">
                            <p class="flex justify-start text-h6 q-mb-none">Our users</p>
                            <q-separator color="white" class="w-100 q-mt-sm q-mb-sm"/>
                            <p>
                                Lorem ipsum dolor sit amet, consetetur sadipscing elitr, 
                                sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, 
                                sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. 
                                Stet clita kasd gubergren, no sea takimata sa.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row q-mb-md">
                    <div class="col-12 col-sm-6 flex justify-end items-center q-pa-md">
                        <div class="text-start">
                            <p class="flex justify-start text-h6 q-mb-none">Our service</p>
                            <q-separator color="white" class="w-100 q-mt-sm q-mb-sm"/>
                            <p>
                                Lorem ipsum dolor sit amet, consetetur sadipscing elitr, 
                                sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, 
                                sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. 
                                Stet clita kasd gubergren, no sea takimata sa.
                            </p>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 flex justify-start items-center q-pa-md">
                        <q-icon name="engineering" size="160px" color="white"/>
                    </div>
                </div>

                <div class="row q-mb-md">
                    <div class="col-12 col-sm-6 flex justify-end items-center q-pa-md">
                        <q-icon name="grading" size="160px" color="white"/>
                    </div>
                    <div class="col-12 col-sm-6 flex justify-start items-center q-pa-md">
                        <div class="text-start">
                            <p class="flex justify-start text-h6 q-mb-none">Others</p>
                            <q-separator color="white" class="w-100 q-mt-sm q-mb-sm"/>
                            <p>
                                Lorem ipsum dolor sit amet, consetetur sadipscing elitr, 
                                sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, 
                                sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. 
                                Stet clita kasd gubergren, no sea takimata sa.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="row q-mb-md">
                    <div class="col-12 col-sm-6 flex justify-end items-center q-pa-md">
                        <div class="text-start">
                            <p class="flex justify-start text-h6 q-mb-none">Others</p>
                            <q-separator color="white" class="w-100 q-mt-sm q-mb-sm"/>
                            <p>
                                Lorem ipsum dolor sit amet, consetetur sadipscing elitr, 
                                sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, 
                                sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. 
                                Stet clita kasd gubergren, no sea takimata sa.
                            </p>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 flex justify-start items-center q-pa-md">
                        <q-icon name="collections_bookmark" size="160px" color="white"/>
                    </div>
                </div>
            </div>
        </SectionDesignColored>
       
        <!-- About -->
        <SectionDesignClear>
            <SectionTitle 
                class="w-title-lg text-center"
                title="Introduction."
            >
                Lorem ipsum dolor sit amet, consetetur sadipscing elitr, 
                sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, 
                sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. 
                Stet clita kasd gubergren, no sea takimata sa.
            </SectionTitle>

            <div class="col-12 flex justify-center w-content-sm">
                <CardSimple title="Why choose us?" class="flex w-card-md">
                    <q-card-section>
                        <p class="q-mt-sm text-left">
                            Lorem ipsum dolor sit amet, consetetur sadipscing elitr, 
                            sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, 
                            sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. 
                            Stet clita kasd gubergren, no sea takimata sa.
                        </p>
                    </q-card-section>
                </CardSimple>
                <CardSimple title="What do we offer?" class="flex w-card-md">
                    <q-card-section>
                        <p class="q-mt-sm text-left">
                            Lorem ipsum dolor sit amet, consetetur sadipscing elitr, 
                            sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, 
                            sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. 
                            Stet clita kasd gubergren, no sea takimata sa.
                        </p>
                    </q-card-section>
                </CardSimple>
                <CardSimple title="Our commitment" class="w-card-md">
                    <q-card-section>
                        <p class="q-mt-sm text-left">
                            Each licence purchased by our users will not only support our commnuity, 
                            but also our environment, as we contribute 12% of our earnings directly to NGO's.  
                        </p>
                    </q-card-section>
                </CardSimple>
            </div>
        </SectionDesignClear>

        <SectionDesignColored class="text-white text-center">
            <SectionTitle 
                class="w-title-lg"
                title="Quick insigth."
            >
                Lorem ipsum dolor sit amet, consetetur sadipscing elitr, 
                sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, 
                sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. 
                Stet clita kasd gubergren, no sea takimata sa.
            </SectionTitle>
            <div class="col-12 flex justify-center text-center">
                <!-- Content -->
                <CardSimple title="Visitors / Month" class="w-card-sm">
                    <q-card-section>
                        <q-icon name="insights" size="72px" color="primary" />
                        <p class="text-primary text-h4">1000</p>
                    </q-card-section>
                </CardSimple>
                <CardSimple title="Our users" class="w-card-sm">
                    <q-card-section>
                        <q-icon name="diversity_1" size="72px" color="primary" />
                        <p class="text-primary text-h4">200</p>
                    </q-card-section>
                </CardSimple>
            </div>
        </SectionDesignColored>

        <!-- Teams -->
        <SectionDesignDefault>
            <SectionTitle 
                class="w-title-lg text-center"
                title="Our Team."
            >
                Lorem ipsum dolor sit amet, consetetur sadipscing elitr, 
                sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, 
                sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. 
                Stet clita kasd gubergren, no sea takimata sa.
            </SectionTitle>
            <div class="col-12 flex justify-center q-mb-lg q-mt-md">
                <div class="text-left">
                    Teams
                </div>
            </div>
        </SectionDesignDefault>
    </PageWrapper>

</template>

<script>
export default {
    name: 'WebAbout',
    components: {
        // Compontent
    },

    data() {
        return {
            //
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
