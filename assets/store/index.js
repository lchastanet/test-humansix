import Vuex from "vuex"
import Vue from "vue"
import createPersistedState from "vuex-persistedstate"

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
      const response = await Vue.prototype.$axios.post("login_check", user)

      const token = response.data.token

      Vue.prototype.$axios.defaults.headers.common = {
        Authorization: `Bearer ${token}`,
      }

      localStorage.setItem("token", token)

      await commit("SET_USER", user.username)
    },
    async LogOut({ commit }) {
      let user = null
      commit("LOGOUT", user)
    },
  },
  plugins: [createPersistedState()],
})
