export const state = () => ({
    mode: '',
})
export const getters = {
    getMode(state) {
        return state.mode
    },
}

export const mutations = {
    setMode(state, mode) {
        state.mode = mode
    },
}
