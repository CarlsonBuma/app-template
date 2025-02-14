'use strict';
import store from "src/stores/user.js";

const fallBackRouteBackpanel = '/';
const routesBackpanel = [
    {
        path: '/admin/dashboard',
        name: 'AdminBackpanel',
        component: () => import('src/pages/admin/AdminBackpanel.vue'),
        beforeEnter: (to, from, next) => {
            if (!store().access.tokens[process.env.APP_ACCESS_ADMIN]) next(fallBackRouteBackpanel);
            else next();
        }
    }, {
        path: '/admin/access',
        name: 'AccessManagement',
        component: () => import('src/pages/access/AdminAccess.vue'),
        beforeEnter: (to, from, next) => {
            if (!store().access.tokens[process.env.APP_ACCESS_ADMIN]) next(fallBackRouteBackpanel);
            else next();
        }
    }, {
        path: '/admin/newsfeed',
        name: 'NewsfeedManagement',
        component: () => import('src/pages/admin/NewsfeedManagement.vue'),
        beforeEnter: (to, from, next) => {
            if (!store().access.tokens[process.env.APP_ACCESS_ADMIN]) next(fallBackRouteBackpanel);
            else next();
        }
    },
];

export default routesBackpanel;
