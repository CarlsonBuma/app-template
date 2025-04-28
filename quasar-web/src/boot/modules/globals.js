'use strict';

/**
 ** Use global variables within app
 *  > Init: "boot/defaults"
 *  > Access: this.$globals
 * 
 * ------------------------------------
 * Define new Globals here if needed
 * ------------------------------------
 */

export const redirects = {
    linkGoolgeMaps: 'https://www.google.com/maps/search/?api=1&query=',
    linkYoutube: process.env.APP_REDIRECT_YOUTUBE,
    linkLinkedin: process.env.APP_REDIRECT_LINKEDIN,
    emailContact: process.env.APP_EMAIL_CONTACT,
    emailLegal: process.env.APP_EMAIL_LEGAL,
};

export const regRules = {
    email: /^(?=[a-zA-Z0-9@._%+-]{6,254}$)[a-zA-Z0-9._%+-]{1,64}@(?:[a-zA-Z0-9-]{1,63}\.){1,8}[a-zA-Z]{2,63}$/,
    passwordPattern: {
        min_length: /^(.{7,255})$/,         // 7 Characters
        capital_letter: /[A-Z]/,            // Capital Letter
        number: /\d/                        // Number
    },
    sanitizeLink: /^(https?:\/\/)?([\w-]+(\.[\w-]+)+)(:[0-9]{1,5})?(\/.*)?$/i
};

export const checkPasswordRequirements = (password, password_confirm) => {
    if (!regRules.passwordPattern.min_length.test(password)) return 'Password must contain more than 7 characters.'
    else if (!regRules.passwordPattern.capital_letter.test(password)) return 'Password must contain 1 capital letter.'
    else if (!regRules.passwordPattern.number.test(password)) return 'Password must contain 1 number.';
    else if(!password_confirm || password !== password_confirm) return 'Password does not match.'
    return;
}

export default {
    redirects,
    regRules,
    checkPasswordRequirements
}
