window._ = require('lodash');


try {
    window.$ = window.jQuery = require('jquery');
    require('bootstrap-sass');
} catch (e) {
}


window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.axios = require('axios');
window.Vue = require('vue');


let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common = {
        'X-CSRF_TOKEN': window.App.csrfToken,
        'X-Requested-With': 'XMLHttpRequest'
    };
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

// window.events = new Vue();
window.flash = function (message, level='success') {
    window.events.$emit('flash',{message,level} );
};

