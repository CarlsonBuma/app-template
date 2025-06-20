<template>

    <PageWrapper showFooter>
        <SectionDesignDefault>
            <SectionSplit>
                <template #left>
                    
                    <SectionTitle 
                        class="w-title"
                        header="Get familiar with our terms-of-use."
                        title="Legal &amp; Compliance"
                    >
                        Here, you’ll find our Terms of Use, which outline your rights and responsibilities when using our platform. 
                        Understanding these terms helps ensure a smooth and secure experience, covering topics like event participation, payments, refunds, and data protection. 
                        
                        <br><br><span class=""><b>Terms of use:</b></span>
                        <br><span class="text-caption">By using our services, you are agreeing with our Legal &amp; Compliance policies.</span>
                        <ul>
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
                            Gigup Solutions GmbH<br>
                            Bern, Switzerland<br>
                            <a href="https://www.gigup.ch" target="_blank">www.gigup.ch</a><br>
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
                These General Terms and Conditions govern the use of our services, ensuring transparency and fairness in your interactions with our platform. 
                By accessing or using our services, you agree to comply with these terms. Please read them carefully before proceeding.
            </SectionTitle>

            <q-separator color="white" class="w-100 q-my-sm"/>
            <CardSimple 
                class="w-card-md q-mx-xs q-mt-lg"
                v-for="(term, index) in $tp.lang('legal_terms')"
                :key="index"
                :title="term.title" 
            >
                <q-card-section >
                    <div class="flex justify-center">
                        <q-icon name="policy" size="72px" color="primary" />
                    </div>
                    <p class="q-mt-md text-start" >{{ term.description }}</p>
                </q-card-section>
            </CardSimple>
        </SectionDesignColored>

        <!-- Data protection -->
        <SectionDesignDefault>
            <SectionTitle 
                class="text-center"
                title="Data Protection Policies"
            >
                To provide you with our services, we may need to collect certain information in specific cases. 
                We are committed to protecting your privacy and minimizing the use of personal data whenever possible.
            </SectionTitle>

            <q-separator class="w-100 q-my-sm"/>
            <CardSimple 
                class="w-card-md q-mx-xs q-mt-lg"
                v-for="(privacy, index) in $tp.lang('legal_privacy')"
                :key="index"
                :title="privacy.title" 
            >
                <q-card-section >
                    <div class="flex justify-center">
                        <q-icon name="policy" size="72px" color="primary" />
                    </div>
                    <p class="q-mt-md text-start" >{{ privacy.description }}</p>
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
