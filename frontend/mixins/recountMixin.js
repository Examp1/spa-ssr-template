import { mapGetters } from 'vuex'
const recount = {
    computed: {
        ...mapGetters({
            getCurrencies: 'globalSettings/getCurrencies',
        }),
    },
    methods: {
        recount(price, oldPrice = null, currency = null) {
            if (currency) {
                const temp = {}
                const productCurrency = this.getCurrencies[
                    currency
                ]?.rate.replace(',', '.')
                const currentCurrency = JSON.parse(
                    localStorage.currentCurrencies
                )?.rate.replace(',', '.')
                temp.price = (
                    (+price * productCurrency) /
                    currentCurrency
                ).toFixed(2)
                temp.oldPrice = (
                    (+oldPrice * productCurrency) /
                    currentCurrency
                ).toFixed(2)
                return temp
            } else {
                return { price, oldPrice }
            }
        },
        totalRecount(price) {
            const currentCurrency = JSON.parse(
                localStorage.currentCurrencies
            )?.rate.replace(',', '.')
            return +(price / currentCurrency).toFixed(2)
        },
    },
}

export const { computed, methods } = recount
