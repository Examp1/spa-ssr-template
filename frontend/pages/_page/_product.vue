<template>
    <div class="product container">
        <div class="product-wrapper">
            <product-photos :props-data="images"></product-photos>
            <product-info
                :translate="translate"
                :prices="prices"
            ></product-info>

        </div>
        <sections-render :props-data="constructor"></sections-render>
    </div>
</template>

<script>
import productInfo from '../../components/e-com/product/product-info.vue'
import SectionsRender from '../../components/sections-render.vue'
import ProductPhotos from '../../components/e-com/product/product-photos.vue'
import { getProductBySlug } from '@/services/productService.js'
export default {
    components: { productInfo, ProductPhotos, SectionsRender },
    async asyncData(ctx) {
        const data = await getProductBySlug(ctx)
        return {
            res: data,
            constructorList: data.constructor,
            meta: data.meta,
            breadcrumbs: data.breadcrumbs,
            translate: data.translate,
            prices: data.prices,
            constructor: data.constructor,
            attributes: data.attributes,
            model: data.model,
            images: data.images,
            similarProduct: data.similar,
            relatedProduct: data.related,
            options: data.options,
            size_grid: data.size_grid,
            preorder_form: data.preorder_form,
            fastForm: data.fast_form,
        }
    },
}
</script>

<style lang="scss" scoped>
.product-wrapper{
    display: grid;
    grid-template-columns: 1fr 1fr;
    .product-info{
        position: sticky;
        top: 0;
        height: fit-content;
    }
}

</style>
