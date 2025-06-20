<template>

    <PageWrapper showFooter>
        <SectionDesignDefault class="flex justify-center w-100">
            <SectionSplit>
                <template #left>
                    <SectionTitle 
                        class="text-start w-title"
                        header="«Where’s your next place to go?»"
                        title="Discover &amp; Connect - Find Your Next Location."
                    >
                        Our vision is to create a vibrant platform where locations connect with their communities. 
                        We strive to make it simple for individuals to discover, join, and enjoy activities at beautiful places worldwide, all while fostering meaningful community connections.
                    
                        <br><br><span class="text-overline"><b>Sitemap</b></span>
                        <ul>
                            <li><a href="#" @click.prevent="$router.push('/about')">About us</a></li>
                            <li><a href="#" @click.prevent="$router.push('/newsfeed')">Newsfeed</a></li>
                            <li><a href="#" @click.prevent="$router.push('/ecosystem')">Our Ecosystem</a></li>
                            <li><a href="#" @click.prevent="$router.push('/legal')">Legal &amp; Compliance</a></li>
                        </ul>
                    </SectionTitle>
                </template>
                <template #right>
                    <CardSimple title="Our Mission" class="text-center w-card-sm" shadow>
                        <q-card-section>
                            <q-icon name="app_shortcut" size="72px" color="primary" />
                            <p class="q-mt-sm">
                                Connecting our community through exciting activities.
                            </p>
                        </q-card-section>
                    </CardSimple>
                    <CardSimple title="Our Community" class="text-center w-card-sm" shadow>
                        <q-card-section>
                            <q-icon name="diversity_1" size="72px" color="primary" />
                            <p class="q-mt-sm">
                                Global and open-minded members contributing to a unique ecosystem.
                            </p>
                        </q-card-section>
                    </CardSimple>
                    <CardSimple title="Our Philosophy" class="text-center w-card-sm" shadow>
                        <q-card-section class="w-100">
                            <q-icon name="tips_and_updates" size="72px" color="primary" />
                            <p class="q-mt-sm">
                                Locations foster communities.
                            </p>
                        </q-card-section>
                    </CardSimple>
                </template>
            </SectionSplit>
        </SectionDesignDefault>

        <!-- Our Services -->
        <SectionDesignColored class="text-white">
            <SectionTitle 
                class="text-center"
                title="Our Commitments"
            >
                As we are very thankful to all our community members who help us contributing locations accross the globe,
                we commit ourself to the following points:
            </SectionTitle>

            <div class="w-content-sm q-pt-md text-left">
                <!-- Step 1 -->
                <div class="row q-mb-md">
                    <div class="col-12 col-sm-6 flex justify-end items-center q-pa-md">
                        <q-icon name="diversity_1" size="160px" color="white"/>
                    </div>
                    <div class="col-12 col-sm-6 flex justify-start items-center q-pa-md">
                        <div class="text-start">
                            <p class="flex justify-start text-h6 q-mb-none">Our community</p>
                            <q-separator color="white" class="w-100 q-mt-sm q-mb-sm"/>
                            <p>
                                Discover new places, meet new people and keep moving.
                                We connect our community to amazing locations!
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row q-mb-md">
                    <div class="col-12 col-sm-6 flex justify-end items-center q-pa-md">
                        <div class="text-start">
                            <p class="flex justify-start text-h6 q-mb-none">Our Locations</p>
                            <q-separator color="white" class="w-100 q-mt-sm q-mb-sm"/>
                            <p>
                                Within your area. For our explorers: Expand your radius and get in touch with new communities.
                                May, you like to explorer the world by locations provided?
                            </p>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 flex justify-start items-center q-pa-md">
                        <q-icon name="public" size="160px" color="white"/>
                    </div>
                </div>

                <div class="row q-mb-md">
                    <div class="col-12 col-sm-6 flex justify-end items-center q-pa-md">
                        <q-icon name="savings" size="160px" color="white"/>
                    </div>
                    <div class="col-12 col-sm-6 flex justify-start items-center q-pa-md">
                        <div class="text-start">
                            <p class="flex justify-start text-h6 q-mb-none">Fundings</p>
                            <q-separator color="white" class="w-100 q-mt-sm q-mb-sm"/>
                            <p>
                                As a little gift back to our community and especially our environment, we decided to spend
                                30% of our profit for environmental initiatives.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </SectionDesignColored>
       
        <!-- About -->
        <SectionDesignClear>
            <SectionTitle 
                class="w-title-lg text-center"
                title="Introduction to our Team."
            >
                {{ $env?.APP_NAME }} was foundet in 2025. 
                Our initial goal is to create an amazing app that allows our community to discover places by connecting people with exciting opportunities. 
                We think it's important to give our members the opportunity to be spontaneous and adventurous by simply finding places they connect with. 
                Our journey starts here 🤖.
            </SectionTitle>

            <div class="col-12 flex justify-center w-content-sm">
                <CardSimple title="Carlson Patrick" class="flex w-card-md">
                    <q-card-section>
                        <p class="q-mt-sm text-left">
                            Our Founder and Owner likes Raves, Festivals and Streetart. May you find him at your next location?
                        </p>
                    </q-card-section>
                </CardSimple>
            </div>
        </SectionDesignClear>

        <!-- Insights -->
        <SectionDesignColored class="text-white text-center">
            <SectionTitle 
                class="w-title-lg"
                title="Quick insigth"
            >
                For more information, you alway welcom to write us directly via e-mail.
            </SectionTitle>
            <div class="col-12 flex justify-center text-center">
                <CardSimple title="Upcomming Events" class="w-card-sm">
                    <q-card-section>
                        <q-icon name="insights" size="72px" color="primary" />
                        <p class="text-primary text-h4">59</p>
                    </q-card-section>
                </CardSimple>
                <CardSimple title="Active Members" class="w-card-sm q-mx-xs">
                    <q-card-section>
                        <q-icon name="diversity_1" size="72px" color="primary" />
                        <p class="text-primary text-h4">768</p>
                    </q-card-section>
                </CardSimple>
                <CardSimple title="Total Fundings" class="w-card-sm">
                    <q-card-section>
                        <q-icon name="foundation" size="72px" color="primary" />
                        <p class="text-primary text-h4 q-mb-none">CHF 0.00</p> 
                    </q-card-section>
                    <q-separator />
                    <q-card-section class="text-right">
                        <span class="text-grey text-caption">Latest update: 15.03.2025</span>
                    </q-card-section>
                </CardSimple>
            </div>
        </SectionDesignColored>
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
        // 
    },

    methods: {
        //
    }
};
</script>
