//window._ = require('lodash');
import InstantSearch from 'vue-instantsearch';
import VueNoty from 'vuejs-noty';
//window.Popper = require('popper.js').default;


import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css';

window.Dayjs = require('dayjs');

window.$ = window.jQuery = require('jquery');
require('bootstrap');


window.Vue = require('vue');

Vue.use(InstantSearch);
Vue.use(VueNoty, {
    layout   : 'bottomCenter',
    theme    : 'metroui',
    closeWith: ['click', 'button'],
    timeout: 5000,
    animation: {
        open : 'animated fadeIn',
        close: 'animated fadeOut'
    }
});

//Vue.use(BootstrapVue);

Vue.config.devtools = true;

let authorizations = require('./authorizations');

Vue.prototype.authorize = function (...params) {
    if (! window.App.signedIn) return false;

    if (typeof params[0] === 'string') {
        return authorizations[params[0]](params[1]);
    }

    return params[0](window.App.user);
};

Vue.prototype.signedIn = window.App.signedIn;


window.axios = require('axios');

window.axios.defaults.headers.common = {
    'X-CSRF-TOKEN': window.App.csrfToken,
    'X-Requested-With': 'XMLHttpRequest'
};

window.events = new Vue();

window.flash = function (message, level = 'success') {
    window.events.$emit('flash', { message, level });
};
