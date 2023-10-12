<template>
    <div class="product-images">
        <img v-for="(image, idx) in propsData" :key="'image'+idx" :src="path(image.image)" alt="">
    </div>
</template>

<script>
    export default {

    }
</script>

<style lang="scss" scoped>
.product-images{
    display: flex;
    flex-direction: column;
    grid-gap: 20px;
    img{
        max-height: 700px;
        object-fit: contain;
    }
}
</style>
