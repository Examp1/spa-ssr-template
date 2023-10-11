<template>
    <div class="category">
        <sections-render
            :props-data="propsData.constructor"
            :home-page="true"
        >
            <template #simpleFirstScreen>
                <first-screen
                    v-if="!hasFirstScreen"
                    :props-data="simpleFirstScreen"
                ></first-screen>
            </template>
            <template #breadcrumbs>
                <app-breadcrumbs :props-data="breadcrumbs" class="container"></app-breadcrumbs>
            </template>
            <template #categoryItems>
                <div class="products-zone container">
                    <app-product-card
                        v-for="(product, idx) in products"
                        :key="'product' + idx"
                        :props-data="product"
                    ></app-product-card>
                </div>
                <app-pagination :props-data="paginateData"></app-pagination>
            </template>
        </sections-render>
    </div>
</template>

<script>
import appBreadcrumbs from '../common/app-breadcrumbs.vue';
import appProductCard from '../e-com/main/app-product-card.vue'
import sectionsRender from '../sections-render.vue';
import appFirstScreen from '~/components/admin-components/main/app-firstScreen.vue'
import appPagination from '~/components/navigations/app-pagination.vue'
export default {
    name: 'CategoryPage',
    components: { appProductCard, sectionsRender, 'first-screen': appFirstScreen, appBreadcrumbs, appPagination },
    data() {
        return {
            meta: this.propsData.meta,
            breadcrumbs: this.propsData.breadcrumbs,
            products: this.propsData.products.data,
            prices: null,
            translate: this.propsData.translate,
            form: this.propsData.preorder_form,
            paginateData: this.propsData.products.paginate,
            done: false,
            hideBtn: false,
            count: 1,
            openOrderModal: false,
            filterData: {
                filter: this.propsData.filter,
                selected: this.propsData.selected,
                additionalFilters: this.propsData.additionalFilters,
                sorts: this.propsData.sorts,
                orders: this.propsData.orders,
            },
        }
    },
    computed: {
        simpleFirstScreen() {
            return {
                title: this.propsData.translate.title,
                text: this.propsData.translate.description,
                ...this.propsData.translate.main_screen,
                image: this.propsData.translate.image,
                image_mob: this.propsData.translate.image_mob,
            }
        },
        hasFirstScreen() {
            return this.propsData.constructor.some(
                (item) => item.component === 'first-screen' && +item.visibility
            )
        },
    },
}
</script>

<style lang="scss" scoped>
.category {
    display: flex;
    grid-gap: 20px;
    //   grid-template-columns: 1fr 3fr;
    //   transition: 1s;
}

.products-zone {
    //   width: calc(100% - 345px);
    padding-bottom: 60px;
    width: 100%;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    @include _768 {
        grid-template-columns: repeat(2, 1fr);
    }
}

.filter {
    max-width: 345px;
    width: 100%;
    background-color: #ccc;
}

button {
    position: fixed;
    right: 0;
    top: 0;
}
</style>
