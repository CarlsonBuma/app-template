'use strict';
import { defineStore } from "pinia";
import { LocalStorage } from 'quasar'
import axios from 'axios';

const useUserStore = defineStore('user', {
    state: () => ({
        access: {
            bearer_token: false,    // Bearer to authorize user
            user: false,            // Access to memberarea
            tokens: {
                'default': {
                    id: 'token',
                    expiration_date: '2024-12-24',
                    quantity: 0,
                    limit_storage: 0
                }
            },
        },
        
        user: {
            id: 0,
            name: '',
            avatar_src: '',
            email: '',
        },
    }),
    
    actions: {

        setAppAccess(access) {
            if(!access?.access_token) return;
            this.access.tokens[access.access_token] = {
                id: access.access_token,
                quantity: access.quantity,
                limit_storage: access?.limit_storage,
                expiration_date: access.expiration_date,
            }
        },

        removeAppAccess(accessToken) {
            this.access.tokens[accessToken] = null;
        },

        setUser(userID, userName, userAvatarSrc, userEmail) {
            this.access.user = true;
            this.user.id = userID;
            this.user.name = userName;
            this.user.avatar_src = userAvatarSrc;
            this.user.email = userEmail;
        },

        /**
         * Set bearer token in local storage
         * After successful login 
         */
        setBearerToken(sessionToken) {
            LocalStorage.set(process.env.APP_SESSION_NAME, sessionToken)
        },

        /**
         * Check current session
         */
        checkBearerTokenSet() {
            return LocalStorage.getItem(process.env.APP_SESSION_NAME) ? true : false
        },

        /**
         * Load bearer token from local storage
         * Set new user session
         */
        setSession() {
            const token = LocalStorage.getItem(process.env.APP_SESSION_NAME);
            if(token) {
                axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
                this.access.bearer_token = true;
            };
        },

        /**
         * remove bearer token from local storage
         */
        removeBearerToken() {
            LocalStorage.remove(process.env.APP_SESSION_NAME)
            this.removeSession()
        },
        
        /**
         * Remove session
         */
        removeSession() {
            axios.defaults.headers.common['Authorization'] = '';
            this.user = {};
            this.access.bearer_token = false
            this.access.user = false;
            this.access.tokens = {
                'default': {
                    id: 'token',
                    expiration_date: '2024-12-24',
                    quantity: 0,
                }
            };
        },
    }
});

export default useUserStore
