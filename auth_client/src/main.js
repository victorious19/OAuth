import Vue from 'vue'
import App from "./App"
import VueRouter from "vue-router"
import VueCookie from "vue-cookie"
import LoginPage from "./components/LoginPage";
import RegisterPage from "./components/RegisterPage";
import ResetPassword from "./components/ResetPassword";
import ForgotPassword from "./components/ForgotPassword";
import Page404 from "./components/404"
import Authentificate from "./components/Authentificate";

Vue.config.productionTip = false

Vue.use(VueRouter)
Vue.use(VueCookie)

const router = new VueRouter({
  mode: 'history',
  routes: [
    {
      path: '/',
      component: Authentificate
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
      path: "*",
      component: Page404
    },
  ],
})
new Vue({
  render: h => h(App),
  router: router
}).$mount("#app");
