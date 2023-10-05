export const state = () => ({
    headerMenu: null,
    footerMenu: null,
    sideBarMenu: null,
    textPageMenu: null,
})

export const getters = {
    getHeaderMenu(state) {
        return state.headerMenu
    },
    getFooterMenu(state) {
        return state.footerMenu
    },
    getSideBarMenu(state) {
        return state.headerMenu2
    },
    getTextPageMenu(state) {
        return state.textPageMenu
    },
}

export const mutations = {
    changeMenuData(state, data) {
        state.headerMenu = data.items[1]
        state.footerMenu = data.items[2]
        // state.sideBarMenu = data.items[43]
        state.textPageMenu = data.items[43]
    },
}

export const actions = {
    setMenuData({ commit }, data) {
        commit('changeMenuData', data)
    },
}
