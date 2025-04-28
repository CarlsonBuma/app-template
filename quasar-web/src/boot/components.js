'use strict';

import { defineBoot } from '#q-app/wrappers'
import PageWrapper from 'src/components/global/PageWrapper.vue';
import PageDrawer from 'src/components/navigation/PageDrawer.vue';
import CardSimple from 'src/components/global/CardSimple.vue';
import FormWrapper from 'src/components/global/FormWrapper.vue';
import DialogWrapper from 'src/components/global/DialogWrapper.vue';
import LoadingData from 'src/components/global/LoadingData.vue';
import NoData from 'src/components/global/NoData.vue';
import SectionTitle from 'components/global/SectionTitle.vue';
import SectionNote from 'src/components/global/SectionNote.vue';
import SectionSplit from 'src/components/global/SectionSplit.vue';
import SectionSplitFix from 'src/components/global/SectionSplitFix.vue';
import SectionDesignDefault from 'src/components/global/SectionDesignDefault.vue';
import SectionDesignClear from 'src/components/global/SectionDesignClear.vue';
import SectionDesignColored from 'src/components/global/SectionDesignColored.vue';

// Navigations
import NavUser from 'src/components/navigation/NavUser.vue';
import NavCockpit from 'src/components/navigation/NavCockpit.vue';
import NavAdmin from 'src/components/navigation/NavAdmin.vue';


export default defineBoot(({ app }) => {
    
    app.component('PageWrapper', PageWrapper)
    app.component('PageDrawer', PageDrawer)
    app.component('CardSimple', CardSimple)
    app.component('FormWrapper', FormWrapper)
    app.component('DialogWrapper', DialogWrapper)
    app.component('LoadingData', LoadingData)
    app.component('NoData', NoData)
    app.component('SectionTitle', SectionTitle)
    app.component('SectionNote', SectionNote)
    app.component('SectionSplit', SectionSplit)
    app.component('SectionSplitFix', SectionSplitFix)
    app.component('SectionDesignDefault', SectionDesignDefault)
    app.component('SectionDesignClear', SectionDesignClear)
    app.component('SectionDesignColored', SectionDesignColored)

    // Navigation
    app.component('NavUser', NavUser)
    app.component('NavCockpit', NavCockpit)
    app.component('NavAdmin', NavAdmin)

});
