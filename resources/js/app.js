import Vue from 'vue';
import {router} from './config/routes';
import './filters/filters';

Vue.config.productionTip = false;

new Vue({
    el: '#app',
    router,
}).$mount('#app');
