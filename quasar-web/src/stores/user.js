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
                }
            },
        },
        user: {
            id: 0,
            name: 'User',
            avatar_src: '',
            email: '',
        },
    }),
    
    actions: {

        setAppAccess(accessToken = '', expirationDate = '', quantity = 0) {
            if(!accessToken || !expirationDate) return;
            this.access.tokens[accessToken] = {
                id: accessToken,
                expiration_date: expirationDate,
                quantity: quantity,
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
            LocalStorage.set(process.env.SESSION_NAME, sessionToken)
        },

        /**
         * Check current session
         */
        checkBearerTokenSet() {
            return LocalStorage.getItem(process.env.SESSION_NAME) ? true : false
        },

        /**
         * remove bearer token from local storage
         */
        removeBearerToken() {
            LocalStorage.remove(process.env.SESSION_NAME)
        },

        /**
         * Load bearer token from local storage
         * Set new user session
         */
        setSession() {
            const token = LocalStorage.getItem(process.env.SESSION_NAME);
            if(token) {
                axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
                this.access.bearer_token = true;
            };
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
