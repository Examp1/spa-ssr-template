export const state = () => ({
    formData: null,
})

export const getters = {
    getFormData: (state) => {
        return state.formData
    },
}

export const mutations = {
    updateFormData: (state, payload) => {
        state.formData = payload
    },
}

export const actions = {
    setFormData({ commit }, formData) {
        commit('updateFormData', formData)
    },
}
