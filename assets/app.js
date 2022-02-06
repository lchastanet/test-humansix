import Vue from "vue"
import VueRouter from "vue-router"
import "bootstrap"
import "bootstrap/dist/css/bootstrap.min.css"
import axios from "axios"
import store from "./store"

import Home from "./components/Home"
import OrdersList from "./components/orders/OrdersList"
import OrderForm from "./components/orders/OrderForm"
import OrderDetails from "./components/orders/OrderDetails"
import ProductsList from "./components/products/ProductsList"
import NavBar from "./components/NavBar"

Vue.use(VueRouter)

axios.defaults.withCredentials = true
axios.defaults.baseURL = "http://localhost:8000/api/"
axios.defaults.headers.common = {
  Authorization: `Bearer ${localStorage.getItem("token")}`,
}
axios.interceptors.response.use(undefined, function (error) {
  if (error) {
    const originalRequest = error.config
    if (error.response.status === 401 && !originalRequest._retry) {
      originalRequest._retry = true
      store.dispatch("LogOut")
      return router.push("/")
    }
  }
})

Vue.config.productionTip = false

Vue.prototype.$axios = axios

const router = new VueRouter({
  mode: "history",
  base: __dirname,
  routes: [
    {
      path: "/",
      component: Home,
    },
    {
      path: "/orders-list",
      component: OrdersList,
    },
    {
      path: "/products-list",
      component: ProductsList,
    },
    {
      path: "/order-form",
      component: OrderForm,
      meta: { requiresAuth: true },
    },
    {
      path: "/order-details/:id",
      name: "orderDetails",
      component: OrderDetails,
    },
  ],
})

router.beforeEach((to, from, next) => {
  if (to.matched.some((record) => record.meta.requiresAuth)) {
    if (store.getters.isAuthenticated) {
      next()
      return
    }
    next("/")
  } else {
    next()
  }
})

const template = `
    <div>
        <NavBar />
        <router-view class="view"></router-view>
    </div>
    `

new Vue({
  store,
  router,
  template,
  el: "#app",
  components: { NavBar },
})
