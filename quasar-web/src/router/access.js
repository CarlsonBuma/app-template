'use strict';
import store from "src/stores/user.js";

const fallBackRouteBackpanel = '/';
const routesAccess = [
    {
        path: "/account/access",
        name: "UserAccess",
        component: () => import('src/pages/access/UserAccess.vue'),
        beforeEnter: (to, from, next) => {
            if (!store().access.user) next('/');
            else next();
        },
    }, {
        path: '/admin/access',
        name: 'AccessManagement',
        component: () => import('src/pages/access/AdminAccess.vue'),
        beforeEnter: (to, from, next) => {
            if (!store().access.tokens[process.env.APP_ACCESS_ADMIN]) next(fallBackRouteBackpanel);
            else next();
        }
    }
];

export default routesAccess;
