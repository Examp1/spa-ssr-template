<template>
    <div class="product-info">
        <h1>{{ translate.name }}</h1>
    </div>
</template>

<script>
export default {
    name: 'ProductInfo',
    props: {
        prices: [Array, Object],
        translate: [Array, Object],
    },
}
</script>

<style lang="scss" scoped></style>
