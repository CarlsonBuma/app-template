'use strict';

import { defineBoot } from '#q-app/wrappers'
import PageWrapper from 'src/components/global/PageWrapper.vue';
import CardSimple from 'src/components/global/CardSimple.vue';
import FormWrapper from 'src/components/global/FormWrapper.vue';
import DialogWrapper from 'src/components/global/DialogWrapper.vue';
import LoadingData from 'src/components/global/LoadingData.vue';
import NoData from 'src/components/global/NoData.vue';


// Design
import SectionTitle from 'components/design/SectionTitle.vue';
import SectionNote from 'src/components/design/SectionNote.vue';
import SectionSplit from 'src/components/design/SectionSplit.vue';
import SectionSplitFix from 'src/components/design/SectionSplitFix.vue';
import SectionDesignDefault from 'src/components/design/SectionDesignDefault.vue';
import SectionDesignClear from 'src/components/design/SectionDesignClear.vue';
import SectionDesignColored from 'src/components/design/SectionDesignColored.vue';
import TriangleLeft from 'src/components/design/TriangleLeft.vue';


export default defineBoot(({ app }) => {
    
    // Components
    app.component('PageWrapper', PageWrapper)
    app.component('FormWrapper', FormWrapper)
    app.component('DialogWrapper', DialogWrapper)
    app.component('LoadingData', LoadingData)
    app.component('NoData', NoData)
    app.component('CardSimple', CardSimple)
    
    // Sections
    app.component('SectionTitle', SectionTitle)
    app.component('SectionNote', SectionNote)
    app.component('SectionSplit', SectionSplit)
    app.component('SectionSplitFix', SectionSplitFix)
    app.component('SectionDesignDefault', SectionDesignDefault)
    app.component('SectionDesignClear', SectionDesignClear)
    app.component('SectionDesignColored', SectionDesignColored)

    // Design
    app.component('TriangleLeft', TriangleLeft)
});
