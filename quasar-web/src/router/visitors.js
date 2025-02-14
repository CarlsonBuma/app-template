'use strict';

const routesVisitors = [
    {
        path: '/',
        redirect: '/landingpage'
    }, {
        path: '/landingpage',
        name: 'LandingPage',
        component: () => import('src/pages/visitor/LandingPage.vue'),
    }, {
        path: "/newsfeed",
        name: "WebNewsfeed",
        component: () => import('src/pages/visitor/WebNewsfeed.vue'),
    }, {
        path: "/about",
        name: "WebAbout",
        component: () => import('src/pages/visitor/WebAbout.vue'),
    }, {
        path: "/pricing",
        name: "WebPricing",
        component: () => import('src/pages/visitor/WebPricing.vue'),
    }, {
        path: "/legal",
        name: "WebLegal",
        component: () => import('src/pages/visitor/WebLegal.vue'),
    }
];

export default routesVisitors;
