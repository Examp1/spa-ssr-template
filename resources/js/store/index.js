import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    src,
  },
  mutations: {
    setSrc(state, data) {
      state.src = data
    }
  },
  actions: {
  },
  modules: {
  }
})
