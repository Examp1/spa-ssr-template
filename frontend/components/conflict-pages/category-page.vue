<template>
  <div class="category container">
    <aside ref="aside" class="filter"></aside>
    <div ref="catalog" class="products-zone">
      <app-product-card
        v-for="(product, idx) in products"
        :key="'product' + idx"
        :props-data="product"
      ></app-product-card>
    </div>
  </div>
</template>

<script>
import appProductCard from '../e-com/main/app-product-card.vue'
export default {
  name: 'CategoryPage',
  components: { appProductCard },
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
