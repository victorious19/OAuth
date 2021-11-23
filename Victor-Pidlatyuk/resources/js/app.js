
require('./bootstrap')
import Vue from 'vue'
import VueRouter from "vue-router"
import RegisterPage from "./components/RegisterPage"
import LoginPage from "./components/LoginPage"
import ForgotPassword from "./components/ForgotPassword"
import ResetPassword from "./components/ResetPassword"
import Profile from "./components/Profile"
import Page404 from "./components/404"
import Products from "./components/Products";
import Home from "./components/Home"
import CreateProduct from "./components/CreateProduct"
import ChangeProduct from "./components/ChangeProduct"
import ProductPage from "./components/ProductPage";
import Orders from "./components/Orders";
import ClientProducts from "./components/ClientProducts";
import Checkout from "./components/Checkout";
import Thank from "./components/Thank";
import Categories from "./components/Categories";
Vue.component('pagination', require('laravel-vue-pagination'));
import VueCookie from 'vue-cookie'

window.Vue = require('vue').default

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default)
// import example from './components/ExampleComponent'

Vue.use(VueRouter)
Vue.use(VueCookie)


const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            component: ClientProducts
        },
        {
            path: '/login',
            component: LoginPage
        },
        {
            path: '/register',
            component: RegisterPage
        },
        {
            path: '/reset-password',
            component: ResetPassword
        },
        {
            path: '/forgot-password',
            component: ForgotPassword
        },
        {
            path: '/profile',
            component: Profile,
            children: [
                {
                    path: '',
                    component: Home
                },
                {
                    path: 'products',
                    component: Products
                },
                {
                    path: 'categories',
                    component: Categories
                },
                {
                    path: '/create-product',
                    component: CreateProduct
                },
                {
                    path: '/change-product/:product_id',
                    name: 'change-product',
                    component: ChangeProduct,
                    props: true
                },
                {
                    path: '/product_page/:product_id',
                    name: 'product_page',
                    component: ProductPage,
                    props: true
                },
                {
                    path: '/orders',
                    component: Orders
                },
            ]
        },
        {
            path: '/products/:product_id',
            component: ProductPage,
            props: true
        },
        {
            path: '/checkout/:product_id',
            component: Checkout,
            props: true
        },
        {
            path: '/thanks',
            component: Thank,
        },
        {
            path: "*",
            component: Page404
        },
    ],
})
const app = new Vue({
    el: '#app',
    router: router
})
