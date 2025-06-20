'use strict';
import store from "src/stores/user.js";

const routesAuth = [
    {
        path: '/',
        component: () => import('src/layouts/InitialLayout.vue'),
        children: [

            {   // User
                path: "/account/settings",
                name: "UserAccount",
                component: () => import('src/pages/user/auth/UserAccount.vue'),
                beforeEnter: (to, from, next) => {
                    if (!store().access.user) next('/');
                    else next();
                },
            }, 

            {   // Access
                path: "/account/access",
                name: "UserAccess",
                component: () => import('src/pages/user/UserAccess.vue'),
                beforeEnter: (to, from, next) => {
                    if (!store().access.user) next('/');
                    else next();
                },
            },
            
            {   // Public
                path: "/create-account",
                name: "CreateNewAccount",
                component: () => import('src/pages/user/auth/CreateAccount.vue'),
            }, {
                path: "/password-reset-request",
                name: "PasswordResetRequest",
                component: () => import('src/pages/user/auth/PasswordResetRequest.vue'),
            }, {
                path: "/password-reset/:email/:key",
                name: "PasswordReset",
                component: () => import('src/pages/user/auth/PasswordReset.vue'),
            }, {
                path: "/email-verification-request/:email",
                name: "EmailVerificationRequest",
                component: () => import('src/pages/user/auth/EmailVerificationRequest.vue'),
            },
            {
                path: "/email-verification/:email/:key",
                name: "EmailVerification",
                component: () => import('src/pages/user/auth/EmailVerification.vue'),
            }, {
                path: "/transfer-account/:email/:key/:transfer",
                name: "TransferAccount",
                component: () => import('src/pages/user/auth/TransferAccount.vue'),
            }, {
                path: "/login",
                name: "UserLogin",
                component: () => import('src/pages/user/auth/UserLogin.vue'),
            },  {
                path: "/authenticate/:email?/:token?",
                name: "UserAuth",
                props: true,
                component: () => import('src/pages/user/auth/UserAuth.vue'),
            }
        ]
    },
];

export default routesAuth;
