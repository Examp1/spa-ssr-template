export const state = () => ({
    settings: null,
    currentCurrency: null
})

export const getters = {
    getSettings(state) {
        return state.settings
    },
    getLogo(state) {
        return state.settings.logotype
    },
    getLogoMob(state) {
        return state.settings.logodark
    },
    getCurrencies(state) {
        return state.settings.checkout.currencies
    },
    getFavoritCities(state) {
        return state.settings.checkout.cities
    },
    getCurrentCurrencies(state) {
        return state.currentCurrency
    },
    getFooter(state) {
        return state.settings.logofooter
    },
    getCopyright(state) {
        const date = new Date()
        const year = date.getFullYear()
        return state.settings.copyright && state.settings.copyright.includes('%year%') ? state.settings.copyright.replace('%year%', year) : state.settings.copyright + ' ' + year
    },
    getMainContact(state) {
        let temp = [];
        if (state.settings && state.settings.contacts.length > 0) {
            state.settings.contacts.forEach(item => {
                if (item.is_main) {
                    temp = item
                }
            });
            return temp
        } else return null;
    },
}

export const mutations = {
    changeSettings(state, settings) {
        state.settings = settings
    },
    changeCurrentCurrencies(state, currency) {
        state.currentCurrency = currency
    },
}

export const actions = {
    setSettings({ commit }, settings) {
        commit('changeSettings', settings)
    },
    setCurrentCurrencies({ commit }, currency) {
        commit('changeCurrentCurrencies', currency)
    },
}
