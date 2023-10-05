export const state = () => ({
    settings: null,
    currentCurrency: null,
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
        return state.settings.copyright &&
            state.settings.copyright.includes('%year%')
            ? state.settings.copyright.replace('%year%', year)
            : state.settings.copyright + ' ' + year
    },
    getThemeConfig(state) {
        return {
            theme_bg_type: state.settings?.theme_bg_type,
            theme_color: state.settings?.theme_color,
            theme_font_style: state.settings?.theme_font_style,
            theme_gradient: state.settings?.theme_gradient,
            theme_gradient2: state.settings?.theme_gradient2,
            theme_noise: state.settings?.theme_noise,
            theme_gradient_deg: state.settings?.theme_gradient_deg,
            theme_gradient_type: state.settings?.theme_gradient_type,
        }
    },
    getBackground(state) {
        let bg = null
        if (state.settings?.theme_bg_type === 'color') {
            bg = `background: ${state.settings?.theme_color}`
        } else if (state.settings?.theme_bg_type === 'gradient') {
            if (state.settings?.theme_gradient_type === 'linear') {
                bg = `background: linear-gradient(${state.settings?.theme_gradient_deg}deg, ${state.settings?.theme_gradient}, ${state.settings?.theme_gradient2});`
            } else {
                bg = `background: radial-gradient(circle, ${state.settings?.theme_gradient}, ${state.settings?.theme_gradient2});`
            }
        }
        return bg
    },
    getMainContact(state) {
        let temp = []
        if (state.settings && state.settings.contacts.length > 0) {
            state.settings.contacts.forEach((item) => {
                if (item.is_main) {
                    temp = item
                }
            })
            return temp
        } else return null
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
