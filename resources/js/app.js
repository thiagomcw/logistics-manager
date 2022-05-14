import Vue from 'vue';
import {router} from './config/routes';
import './filters/filters';

import DatePicker from 'vue2-datepicker';

Vue.component('date-picker', DatePicker);

Vue.config.productionTip = false;

new Vue({
    el: '#app',
    router,
}).$mount('#app');
