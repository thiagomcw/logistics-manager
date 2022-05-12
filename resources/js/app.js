import Vue from 'vue'
import {router} from './config/routes';

Vue.config.productionTip = false;

new Vue({
    el: '#app',
    router,
}).$mount('#app');
