require('./bootstrap');

// window.Vue = require('vue');
 window.Vue = require('vue').default;
Vue.component('flash', require('./components/Flash.vue').default);
Vue.component('replies', require('./components/Replies').default);
Vue.component('thread-view',require('./pages/Thread.vue'));
const app = new Vue({
    el: '#app'
});
