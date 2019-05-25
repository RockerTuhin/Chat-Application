/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
///for Vuetify
import Vue from 'vue'
import Vuetify from 'vuetify'
Vue.use(Vuetify)
import 'vuetify/dist/vuetify.min.css' // Ensure you are using css-loader
///end for Vuetify

///For auto scrolling
/*For using vue chat scroll,this is used from this source https://github.com/theomessin/vue-chat-scroll*/ 
// import Vue from 'vue' //it is commented because we use it once before
import VueChatScroll from 'vue-chat-scroll'
Vue.use(VueChatScroll)
/*End of For using vue chat scroll,this is used from this source https://github.com/theomessin/vue-chat-scroll*/
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('group-chat', require('./components/group-chat.vue').default);
Vue.component('private-chat', require('./components/private-chat.vue').default);

//FOR USING file-upload COMPONENT GLOBALLY
const VueUploadComponent = require('vue-upload-component');
Vue.component('file-upload',VueUploadComponent);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
