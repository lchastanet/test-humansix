import Vuex from "vuex"
import Vue from "vue"
import createPersistedState from "vuex-persistedstate"
import axios from "axios"

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        user: null,
    },
    mutations: {
        SET_USER(state, username) {
            state.user = username
        },
        LOGOUT(state, user) {
            state.user = user
        },
    },
    getters: {
        isAuthenticated: (state) => !!state.user,
        StateUser: (state) => state.user,
    },
    actions: {
        async LogIn({ commit }, user) {
            await axios.post("login_check", user)
            await commit("SET_USER", user.username)
        },
        async LogOut({ commit }) {
            let user = null
            commit("LOGOUT", user)
        },
    },
    plugins: [createPersistedState()],
})
