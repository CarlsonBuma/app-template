'use strict';
import store from "src/stores/user.js";

const fallBackRouteCockpit = '/';
const routesCockpit = [
    {
        path: '/cockpit',
        component: () => import('src/layouts/CockpitLayout.vue'),
        beforeEnter: (to, from, next) => {
            if (!store().access.tokens[process.env.APP_ACCESS_COCKPIT]) next(fallBackRouteCockpit);
            else next();
        },
        children: [
            {
                path: '',
                name: 'CockpitDashboard',
                component: () => import('src/pages/cockpit/CockpitDashboard.vue'),
            }, {
                path: 'impressum',
                name: 'CockpitImpressum',
                component: () => import('src/pages/cockpit/CockpitImpressum.vue'),
            },
        ]
    },
];

export default routesCockpit;
