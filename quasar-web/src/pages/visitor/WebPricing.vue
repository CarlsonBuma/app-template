<template>

    <PageWrapper noMargin showFooter>
        <SectionDesignDefault>
            <SectionSplit>
                <template #left>
                    <SectionTitle 
                        header="Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam."
                        title="Lorem ipsum dolor sit amet, consetetur sadipscing elitr."
                        subtitle="Lorem ipsum etcetera."
                    >
                        Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore 
                        et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. 
                        Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, 
                        consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, 
                        sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.
                    </SectionTitle>
                </template>
                <template #right>
                    <CardService
                        class="w-card-lg"
                        iconName="badge"
                        iconSize='220px'
                        price='Free'
                        priceTag='forever'
                        title='Title'
                        description="
                            Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore 
                            et dolore magna aliquyam erat, sed diam voluptua. At vero eos et..
                        "
                        :items="[{
                            icon: 'done_all',
                            title: 'Personal Avatar',
                            description: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy.',
                        }]"
                    >
                        <template #actions>
                            <q-btn 
                                flat 
                                :disable="$user.access.user" 
                                color="primary" 
                                icon="auto_awesome" 
                                @click="$emit('authorize')" 
                                :label="$user.access.user ? 'Access granted' : 'Login'" 
                            />
                        </template>
                    </CardService>
                </template>
            </SectionSplit>
        </SectionDesignDefault>

        <SectionDesignColored>
            <SectionTitle 
                class="text-center q-pt-lg text-white"
                title="Our services."
                subtitle="Join our community! Get access to the proivded services."
            />
            <div class="row w-100 justify-center">

                <!-- Price 1 -->
                <CardService
                    class="w-card-lg"
                    iconName="business"
                    iconSize='220px'
                    price='CHF 249.00'
                    priceTag='/12 month'
                    title='My Cockpit'
                    description="
                        Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore 
                        et dolore magna aliquyam erat, sed diam voluptua. At vero eos et.
                    "
                    :items="[{
                        icon: 'done_all',
                        title: 'Your cockpit',
                        description: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore.',
                    }]"
                >
                    <template #actions>
                        <q-btn 
                            v-if="$user.access.user"
                            @click="$router.push('/account/access')" 
                            label="See access" 
                            flat 
                            color="primary" 
                            icon="auto_awesome" 
                        />
                    </template>
                </CardService>

                <!-- Support -->
                <div class="w-card-lg"> 
                    <CardSimple>
                        <div class="flex justify-center">
                            <q-icon name="support_agent" color="primary" size="220px" />
                        </div>
                        <q-card-section>
                            <div class="row no-wrap items-center">
                                <div class="col text-h6 ellipsis">
                                    Support
                                </div>
                            </div>
                        </q-card-section>
                        <q-separator class="q-mb-md"/>
                        <q-card-section class="row align-top justify-center text-center">
                            <p>We are here to support you. Please do not hesitate to contact us via
                                <span>
                                    <q-icon name="email" class="q-mr-xs" />
                                    <a :href="'mailto:' + $globals.redirects.emailContact">{{ $globals.redirects.emailContact }}</a>
                                </span>.
                            </p>
                        </q-card-section>
                    </CardSimple>
                </div>
            </div>
        </SectionDesignColored>
    </PageWrapper>

</template>

<script>
import CardService from 'components/CardService.vue';

export default {
    name: 'WebPricing',
    components: {
        CardService
    },

    emits: [
        'authorize'
    ],

    setup() {
        return {
            // Code
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
    },
};
</script>
