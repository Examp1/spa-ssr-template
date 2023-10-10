<template>
  <section class="category-w">
    <div class="section-header">
      <div class="title">Популярні категорії</div>
    </div>
    <div class="category_items">
      <app-category-item
        v-for="(category, idx) in notEmptyItems"
        :key="'category' + idx"
        :props-data="category"
      ></app-category-item>
    </div>
  </section>
</template>

  <script>
import appCategoryItem from '../main/app-category-item.vue'

export default {
  name: 'CategoriesW',
  components: { appCategoryItem },
  computed: {
    notEmptyItems() {
      return this.propsData.list.filter((el) => {
        return !!el
      })
    },
  },
}
</script>

  <style lang="scss" scoped>
  .section-header{
    margin-bottom: 50px;
  }
.category-w {
  margin-bottom: 70px;
  @include _480 {
    margin-bottom: 50px;
  }
}
.category_items {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  grid-gap: 50px;
  @include _768 {
    grid-gap: 15px;
    grid-template-columns: repeat(2, 1fr);
  }
}
</style>
