import Vue from "vue"
import VueRouter from "vue-router"
import "bootstrap"
import "bootstrap/dist/css/bootstrap.min.css"

import Home from "./components/Home"
import OrdersList from "./components/OrdersList"
import NavBar from "./components/NavBar"

Vue.use(VueRouter)

const router = new VueRouter({
    mode: "history",
    base: __dirname,
    routes: [
        { path: "/", component: Home },
        { path: "/orders-list", component: OrdersList },
    ],
})

const template = `
    <div>
        <NavBar />
        <router-view class="view"></router-view>
    </div>
    `

new Vue({
    router,
    template,
    el: "#app",
    components: { NavBar, Home, OrdersList },
})
