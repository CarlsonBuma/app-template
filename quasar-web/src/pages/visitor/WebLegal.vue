<template>

    <PageWrapper noMargin showFooter>
        <SectionDesignDefault>
            <SectionSplit>
                <template #left>
                    <SectionTitle 
                        class="w-title"
                        header="*by using our services, you are agreeing with our Legal &amp; Compliance policies."
                        title="Information about our Legal &amp; Compliance policies."
                    >
                        Lorem ipsum dolor sit amet, consetetur sadipscing elitr, 
                        sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, 
                        sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. 
                        Stet clita kasd gubergren, no sea takimata sa.

                        <ul class="text-subtitle1">
                            <li>Terms &amp; Conditions</li>
                            <li>Data protection policies</li>
                        </ul>
                    </SectionTitle>
                </template>
                <template #right>
                    <CardFocus 
                        class="w-card-lg"
                        icon="business" 
                        title="Impressum" 
                        description="This plattform is provided by:"
                    >
                        <span>
                            Some Template<br>
                            Some Creditss<br>
                            CH-8000 Zurich<br>
                        </span>
                        <template #note>
                            <p class="q-mb-none">
                                <span>Contact us: <a :href="'mailto:' + $globals.redirects.emailLegal">{{ $globals.redirects.emailLegal }}</a></span>
                            </p>
                        </template>
                    </CardFocus>
                </template>
            </SectionSplit>
        </SectionDesignDefault>

        <!-- Legal & Compliance -->
        <SectionDesignColored>
            <SectionTitle 
                class="text-center text-white"
                title="Terms &amp; Conditions."
            >
                Before you start using our services, please read our Terms &amp; Conditions carefully.
                Lorem ipsum dolor sit amet, consetetur sadipscing elitr, 
                sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, 
                sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.
            </SectionTitle>

            <CardSimple 
                class="w-card-md"
                v-for="(term, index) in $tp.lang('legal_terms')"
                :key="index"
                :title="term.title" 
            >
                <q-card-section >
                    <div class="flex justify-center">
                        <q-icon name="policy" size="72px" color="primary" />
                    </div>
                    <p class="q-mt-md text-start">{{ term.description }}</p>
                </q-card-section>
            </CardSimple>
        </SectionDesignColored>

        <!-- Data protection -->
        <SectionDesignDefault>
            <SectionTitle 
                class="text-center"
                title="Data protection policies."
                subtitle="Here we declare, how we are colleting and processing your data."
            />
            <CardSimple 
                class="w-card-md"
                v-for="(privacy, index) in $tp.lang('legal_privacy')"
                :key="index"
                :title="privacy.title" 
            >
                <q-card-section >
                    <div class="flex justify-center">
                        <q-icon name="policy" size="72px" color="primary" />
                    </div>
                    <p class="q-mt-md text-start">{{ privacy.description }}</p>
                </q-card-section>
            </CardSimple>
        </SectionDesignDefault>
    </PageWrapper>

</template>

<script>
import CardFocus from 'components/CardFocus.vue';

export default {
    name: 'WebLegal',
    components: {
        CardFocus
    },

    setup() {
        return {
            //
        }
    },

    data () {
        return {
            // 
        }
    },

    mounted() {
        // 
    },
};
</script>
