<template>
    <a v-if="to === null || to === ''"><slot /></a>
    <nuxt-link v-else-if="!isExternal" :to="toUrl" :target="target">
        <slot></slot>
    </nuxt-link>
    <a v-else-if="to && to !== ''" :href="to" :target="target"><slot /></a>
</template>

<script>
export default {
    name: 'AppLink',
    props: {
        to: {
            type: [Object, String],
            default: null,
        },
        target: {
            type: String,
            default: '',
        },
    },
    computed: {
        isExternal() {
            if (typeof this.to === 'string') {
                if (this.isExtUrl(this.to)) return true
                else return false
            } else if (typeof this.to === 'object') {
                return false
            } else {
                return null
            }
        },
        toUrl() {
            return this.to === null ? '' : this.to
        },
    },
    methods: {},
}
</script>

<style lang="scss" scoped></style>
