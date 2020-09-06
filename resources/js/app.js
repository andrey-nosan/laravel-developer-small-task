/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
window.Vue.use(VueRouter);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('pagination', require('laravel-vue-pagination'));

import MessageIndexComponent from './components/message/MessageIndexComponent.vue';
import MessageCreateComponent from './components/message/MessageCreateComponent.vue';
import MessageEditComponent from './components/message/MessageEditComponent.vue';

const routes = [
    {
        path: '/',
        components: {
            messageIndex: MessageIndexComponent
        }
    },
    {path: '/create', component: MessageCreateComponent, name: 'createMessage'},
    {path: '/message/edit/:id', component: MessageEditComponent, name: 'editMessage'},
]

const router = new VueRouter({ routes })

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({ router }).$mount('#app')
