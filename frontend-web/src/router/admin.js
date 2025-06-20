'use strict';
import store from "src/stores/user.js";

const fallBackRouteBackpanel = '/';
const routesBackpanel = [
    {
        path: '/admin',
        component: () => import('src/layouts/AdminLayout.vue'),
        beforeEnter: (to, from, next) => {
            if (!store().access.tokens[process.env.APP_ACCESS_ADMIN]) next(fallBackRouteBackpanel);
            else next();
        },
        children: [
            {
                path: '',
                name: 'AdminBackpanel',
                component: () => import('src/pages/admin/AdminBackpanel.vue'),
                
            }, {
                path: 'newsfeed',
                name: 'AdminNewsfeeder',
                component: () => import('src/pages/admin/AdminNewsfeeder.vue'),
            }, {
                path: 'access',
                name: 'AdminAccessManagement',
                component: () => import('src/pages/admin/AdminAccessManagement.vue'),
            }
        ]
    },
];

export default routesBackpanel;
