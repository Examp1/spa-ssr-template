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
            <template #categoryItems>
                <div
                    class="products-zone container"
                >
                    <app-product-card
                        v-for="(product, idx) in products"
                        :key="'product' + idx"
                        :props-data="product"
                    ></app-product-card>
                </div>
            </template>
        </sections-render>
    </div>
</template>

<script>
import appProductCard from '../e-com/main/app-product-card.vue'
import sectionsRender from '../sections-render.vue';
import appFirstScreen from '~/components/admin-components/main/app-firstScreen.vue'
export default {
    name: 'CategoryPage',
    components: { appProductCard, sectionsRender, 'first-screen': appFirstScreen },
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
    margin-top: 80px;
    width: 100%;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
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
