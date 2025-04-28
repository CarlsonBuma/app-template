'use strict';
import store from "src/stores/user.js";

const routesUser = [
    {
        path: '/user',
        redirect: '/user/dashboard'
    }, {
        path: "/user/dashboard",
        name: "UserDashboard",
        component: () => import('src/pages/user/UserDashboard.vue'),
        beforeEnter: (to, from, next) => {
            if (!store().access.user) next('/');
            else next();
        },
    }, {
        path: "/account/access",
        name: "UserAccess",
        component: () => import('src/pages/access/UserAccess.vue'),
        beforeEnter: (to, from, next) => {
            if (!store().access.user) next('/');
            else next();
        },
    },
];

export default routesUser;
