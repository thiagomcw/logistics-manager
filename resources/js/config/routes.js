import Vue from 'vue';
import VueRouter from 'vue-router';

import Master from '../components/Master.vue'
import PackagesIndex from '../views/packages/Index.vue'
import PackagesForm from '../views/packages/Form.vue'
import DeliveryMapIndex from '../views/delivery-map/Index.vue'

Vue.use(VueRouter);

export const appRoutes = [
    {
        path: '/',
        name: 'packages.index',
        component: PackagesIndex,
        title: 'Packages'
    },
    {
        path: '/packages-form',
        name: 'packages.form',
        component: PackagesForm,
        title: 'Package Form'
    },
    {
        path: '/delivery-map',
        name: 'delivery-map.index',
        component: DeliveryMapIndex,
        title: 'Delivery Map'
    },
];

export const routes = [
    {
        path: '',
        component: Master,
        children: appRoutes
    }
];

export const router = new VueRouter({
    routes,
    mode: 'history'
});
