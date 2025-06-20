'use strict';

/**
 ** Request Handling
 *  > Init: "boot/defaults"
 *  > Access: this.$toast()
 *
 * CTA:
 *  > loading(): waiting for response
 *  > done(): close waiting notification
 *  > success(): response is successful
 *  > error(): there was an error
 *      > UI Error vs. Server Error 
 *      > errorHandling(): process accordingly     
 */

import { Loading, QSpinnerGears, Notify } from 'quasar';
import store from "src/stores/user.js";

export default class ResponseHandler {

    /**
     * Hanlding Server Response
     *  > Default Attributes
     *  > ErrorHandling 
     * @param {Object} router 
     * @param {Object} app 
     */
    constructor(router, app) {
        this.loading = false;
        this.app = app;
        this.message = '';
        this.router = router;
        this.progressBar = null;
        this.notify = null;
        this.position = 'bottom-right';
        this.durationSuccess = 1900;
        this.durationError = 5500;
        this.class = "toaster-container"
        this.defaultLoadMessage = "Processing data. Please wait..."
        this.defaultSuccessMessage = "Success.";
        this.defaultErrorMessage = "Ops, some error occured.";
    }

    /**
     * Error Server Responses are managed here
     * According to Server Middleware-Definitions
     *
     * @param {Object} serverResponse 
     * @param {Object} router 
     */
     errorHandling(serverResponse, router, redirect) {
        
        // Ongoing subscriptions
        if(serverResponse.status === 422 && serverResponse.data.status === 'active_subscriptions') {
            router.push('/account/access');
            throw serverResponse.data.message ?? 'Please cancel active subscriptions.'
        }

        // No access to Feature
        else if(serverResponse.status === 401 && serverResponse.data.status === 'no_access_to_feature') {
            store().removeAppAccess(serverResponse.data.access_token);
            router.push('/');
            throw serverResponse.data.message ?? 'Access denied.'
        }
        
        // Email not verified
        else if(serverResponse.status === 401 && serverResponse.data.status === 'email_not_verified') {
            store().removeBearerToken();
            router.push({
                name: 'EmailVerificationRequest', 
                params: { 
                    email: serverResponse.data.email,
                }
            });
            throw serverResponse.data.message;
        }

        // No access
        else if(serverResponse.status === 401) {
            store().removeBearerToken();
            
            if(redirect) router.push('/');
            throw serverResponse.data.message 
                ?? serverResponse.statusText 
                    ?? 'Hmm, some error occured. Please try again.'
        }

        else {
            throw serverResponse.data.message 
                ?? serverResponse.statusText 
                    ?? 'Hmm, some error occured. Please try again.'
        }
    }

    showNotify(message, type, duration) {
        this.notify = Notify.create({
            position: this.position,
            type: type,
            message: message,
            timeout: duration,
            classes: this.class
        });
    }

    load(message = this.defaultLoadMessage) {
        this.loading = true;
        Loading.show({
            boxClass: 'page-loading-block',
            spinner: QSpinnerGears,
            message: message,
        })
        return true;
    }

    done() {
        this.loading = false;
        Loading.hide();
        return false;
    }

    success(successMessage = this.defaultSuccessMessage) {
        this.loading = false;
        this.done();
        if(this.notify) this.notify();
        this.showNotify(successMessage, 'positive', this.durationSuccess)
        return successMessage;
    }

    /**
     * Handle error
     *  > String (UI error) 
     *  > Object (Serverresponse)
     *      > Handle Response Error Status
     *
     * @param {*} responseError String | Object
     * @return { String } 
     */
    error(responseError, redirect = true) {
        try {
            this.loading = false;
            this.done();

            // UI error
            if (typeof responseError === 'string') 
                this.message = responseError
            
            // Error by response
            else if (typeof responseError === 'object') {
                // Error response by server
                if(responseError.data) {
                    this.errorHandling(responseError, this.router, redirect);
                    this.message = responseError.data.message 
                        ?  responseError.data.message
                        :  responseError.status
                            ? responseError.status
                            : this.defaultErrorMessage;
                } 
                
                // Error response by client
                else if (responseError.message) {
                    this.message = responseError.message;
                }
            }
        } catch (error) {
            this.message = error.message ?? error;
        } finally {
            try {
                if(this.notify) this.notify();
                this.showNotify(this.message, 'negative', this.durationError)
            } catch (error) {
                console.log('response.handling.error', error)
            }
        }
        
        console.log('response.error', this.message)
        return this.message;
    }
}
