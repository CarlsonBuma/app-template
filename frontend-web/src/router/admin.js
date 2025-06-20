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
        path: '/admin/newsfeed',
        name: 'AdminNewsfeeder',
        component: () => import('src/pages/admin/AdminNewsfeeder.vue'),
        beforeEnter: (to, from, next) => {
            if (!store().access.tokens[process.env.APP_ACCESS_ADMIN]) next(fallBackRouteBackpanel);
            else next();
        }
    }, {
        path: '/admin/access',
        name: 'AdminAccessManagement',
        component: () => import('src/pages/admin/AdminAccessManagement.vue'),
        beforeEnter: (to, from, next) => {
            if (!store().access.tokens[process.env.APP_ACCESS_ADMIN]) next(fallBackRouteBackpanel);
            else next();
        }
    }
];

export default routesBackpanel;
