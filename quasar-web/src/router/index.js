'use strict';
import { defineRouter } from '#q-app/wrappers'
import { createRouter, createMemoryHistory, createWebHistory, createWebHashHistory } from 'vue-router';
import routesAuth from './auth';
import routesAccess from './access';
import routesUser from './user';
import routesCockpit from './cockpit';
import routesBackpanel from './admin';
import routesVisitors from './visitors';

export default defineRouter(function (/* { store, ssrContext } */) {
    const createHistory = process.env.SERVER
        ? createMemoryHistory
        : (process.env.APP_VUE_ROUTER_MODE === 'history' ? createWebHistory : createWebHashHistory);

    // Default-Routes
    const routes = [{
        path: '/:catchAll(.*)*',
        component: () => import('pages/ErrorNotFound.vue')
    }];

    // Routes
    routes.push(...routesAuth);
    routes.push(...routesAccess);
    routes.push(...routesUser);
    routes.push(...routesCockpit);
    routes.push(...routesBackpanel);
    routes.push(...routesVisitors);

    // Init Router
    const Router = createRouter({
        scrollBehavior: () => ({ left: 0, top: 0 }),
        routes,
        history: createHistory(process.env.VUE_ROUTER_BASE)
    });

    return Router;
})
