import Vue from 'vue'
import nuxtStorage from 'nuxt-storage'
import { mapActions, mapGetters, mapMutations } from 'vuex'
export const order = {
    computed: {
        ...mapGetters({
            getCurrentCurrencies: 'globalSettings/getCurrentCurrencies',
            getBearerToken: 'personalAccount/getBearerToken',
            isLogin: 'personalAccount/isLogin',
        }),
        prefix() {
            return this.getCurrentCurrencies
                ? this.getCurrentCurrencies.prefix
                : null
        },
        suffix() {
            return this.getCurrentCurrencies
                ? this.getCurrentCurrencies.suffix
                : null
        },
    },
    methods: {
        ...mapMutations({
            addToWishList: 'wishlist/addToWishList',
            removeFromWishList: 'wishlist/removeFromWishList',
            setWishListFromLocalStorage: 'wishlist/setWishListFromLocalStorage',
        }),
        ...mapActions({
            updateCart: 'cart/updateCart',
            setCartCount: 'cart/setCartCount',
            setCartId: 'cart/setCartId',
            setCartModal: 'cart/setCartModal',
        }),
        // cart
        makeid() {
            let text = ''
            const possible = 'abcdefghijklmnopqrstuvwxyz'

            for (let i = 0; i < 10; i++)
                text += possible.charAt(
                    Math.floor(Math.random() * possible.length)
                )

            return text
        },
        async getCount() {
            const option = {
                lang: this.locale,
                coupon: localStorage.coupon || '',
            }
            if (localStorage.cartId) {
                option.cart_id = localStorage.cartId.replace(/"/gm, '')
            }
            const res = await this.$axios.$post(
                '/api/cart/count',
                option,
                this.getBearerToken
            )
            this.setCartCount(res.data)
            this.updateCart()
        },
        async deleteProduct(id) {
            const res = await this.$axios.post('/api/cart/delete', {
                id,
                coupon: localStorage.coupon || '',
                cart_id: localStorage.cartId.replace(/"/gm, ''),
                lang: this.locale,
            })
            if (!localStorage.cartId) {
                this.setCartId(res.data.data.cart_id)
            }
            this.cart = res.data.data.products
            this.cartAll = res.data.data
            nuxtStorage.localStorage.setData('cart', res.data.data, 100, 'h')
            this.getCount()
            return res.data.data
        },
        async addToCart(prodId, id) {
            const options = {
                product_id: prodId,
                count: 1,
                lang: this.locale,
            }
            if (id) {
                options.option_id = id
            }
            if (localStorage.cartId) {
                options.cart_id = localStorage.cartId.replace(/"/gm, '')
            }
            const res = await this.$axios.post('/api/cart/add', options)
            if (!localStorage.cartId) {
                this.setCartId(res.data.data.cart_id)
            }
            this.getCount()
            nuxtStorage.localStorage.setData('cart', res.data.data, 100, 'h')

            this.setCartModal(true)
        },
        async plus(prodId, ev) {
            const prodCount = ++ev.target.parentElement.querySelector('input')
                .value
            const res = await this.$axios.post('/api/cart/update', {
                id: prodId,
                coupon: localStorage.coupon || '',
                cart_id: localStorage.cartId.replace(/"/gm, ''),
                count: prodCount,
                lang: this.locale,
            })

            this.cart = res.data.data.products
            this.cartAll = res.data.data
            nuxtStorage.localStorage.setData('cart', res.data.data, 100, 'h')

            this.getCount()

            return res.data.data
        },
        async changeValue(prodId, ev) {
            const prodCount = ev.target.value
            const res = await this.$axios.post('/api/cart/update', {
                id: prodId,
                coupon: localStorage.coupon || '',
                cart_id: localStorage.cartId.replace(/"/gm, ''),
                count: prodCount,
                lang: this.locale,
            })

            this.cart = res.data.data.products
            this.cartAll = res.data.data
            nuxtStorage.localStorage.setData('cart', res.data.data, 100, 'h')

            this.getCount()

            return res.data.data
        },
        async minus(prodId, ev) {
            let prodCount = ev.target.parentElement.querySelector('input').value
            if (prodCount > 0) {
                --prodCount
            } else {
                prodCount = 1
            }
            const res = await this.$axios.post('/api/cart/update', {
                id: prodId,
                coupon: localStorage.coupon || '',
                cart_id: localStorage.cartId.replace(/"/gm, ''),
                count: prodCount,
                lang: this.locale,
            })

            this.cart = res.data.data.products
            this.cartAll = res.data.data
            nuxtStorage.localStorage.setData('cart', res.data.data, 100, 'h')

            this.getCount()

            return res.data.data
        },
        // cart end
        async updateWishlistState(id) {
            if (process.client) {
                if (this.isLogin) {
                    const { data } = await this.$axios.post(
                        '/api/profile/wishlist/update',
                        {
                            product_id: id,
                        },
                        this.getBearerToken
                    )
                    localStorage.wishList = JSON.stringify(
                        data.data.wishlist_product_ids
                    )
                    this.setWishListFromLocalStorage(
                        data.data.wishlist_product_ids
                    )
                } else {
                    const w = localStorage.wishList
                        ? JSON.parse(localStorage.wishList)
                        : []
                    const itemIndex = w.indexOf(id)
                    if (itemIndex >= 0) {
                        w.splice(itemIndex, 1)
                        this.removeFromWishList(id)
                    } else {
                        w.push(id)
                        this.addToWishList(id)
                    }
                    localStorage.setItem('wishList', JSON.stringify(w))
                }
                this.$forceUpdate()
            }
        },
    },
}
Vue.mixin(order)
