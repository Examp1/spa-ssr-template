export const state = () => ({
  formOpen: false
})

export const getters = {
  isFormOpen: state => {
    return state.formOpen
  }
}

export const mutations = {
  updateformState: (state, payload) => {
    state.formOpen = payload
  }
}

export const actions = {
  openForm({ commit }, payload) {
    commit('updateformState', payload)
  }
}
