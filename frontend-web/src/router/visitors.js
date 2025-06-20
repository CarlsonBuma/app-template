'use strict';

const routesVisitors = [
    {
        path: '/',
        component: () => import('src/layouts/InitialLayout.vue'),
        children: [
            {
                path: 'welcome',
                name: 'WebWelcome',
                component: () => import('src/pages/visitor/WebWelcome.vue'),
            }, {
                path: "newsfeed",
                name: "WebNewsfeed",
                component: () => import('src/pages/visitor/WebNewsfeed.vue'),
            }, {
                path: "about",
                name: "WebAbout",
                component: () => import('src/pages/visitor/WebAbout.vue'),
            }, {
                path: "legal",
                name: "WebLegal",
                component: () => import('src/pages/visitor/WebLegal.vue'),
            } 
        ]
    },
];

export default routesVisitors;
