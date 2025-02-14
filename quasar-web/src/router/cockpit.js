'use strict';
import store from "src/stores/user.js";

const fallBackRouteCockpit = '/';
const routesCockpit = [
    {
        path: '/cockpit/dashboard',
        name: 'CockpitDashboard',
        component: () => import('src/pages/cockpit/CockpitDashboard.vue'),
        beforeEnter: (to, from, next) => {
            if (!store().access.tokens[process.env.APP_ACCESS_COCKPIT]) next(fallBackRouteCockpit);
            else next();
        }
    }, {
        path: '/cockpit/profile',
        name: 'CockpitProfile',
        component: () => import('src/pages/cockpit/CockpitProfile.vue'),
        beforeEnter: (to, from, next) => {
            if (!store().access.tokens[process.env.APP_ACCESS_COCKPIT]) next(fallBackRouteCockpit);
            else next();
        }
    },
];

export default routesCockpit;
