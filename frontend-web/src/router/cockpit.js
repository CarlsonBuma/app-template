'use strict';
import store from "src/stores/user.js";

const fallBackRouteCockpit = '/';
const routesCockpit = [{
        path: '/cockpit',
        redirect: '/cockpit/profile'
    }, 
    
    {
        path: '/cockpit/profile',
        name: 'CockpitProfile',
        component: () => import('src/pages/cockpit/CockpitProfile.vue'),
        beforeEnter: (to, from, next) => {
            if (!store().access.user) next(fallBackRouteCockpit);
            else next();
        }
    },
];

export default routesCockpit;
