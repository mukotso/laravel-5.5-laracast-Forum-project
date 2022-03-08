require('./bootstrap');

// window.Vue = require('vue');
 window.Vue = require('vue').default;
Vue.component('flash', require('./components/Flash.vue').default);
Vue.component('reply', require('./components/Reply.vue').default);
const app = new Vue({
    el: '#app'
});
