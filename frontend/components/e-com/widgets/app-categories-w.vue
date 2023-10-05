<template>
  <section class="category-w">
    <div class="section-header">
      <div class="title">Популярні категорії</div>
      <a href="" class="view-all">Всі категорії </a>
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
.category-w {
  margin-bottom: 70px;
  &_items {
    display: flex;
    justify-content: space-between;
    margin: 0 -10px;
    @include _991 {
      flex-wrap: wrap;
      margin: 0 -5px;
      justify-content: flex-start;
    }
  }
  @include _480 {
    margin-bottom: 50px;
  }
}
.category_items {
  display: grid;
  grid-template-columns: repeat(6, 1fr);
  @include _991 {
    grid-template-columns: repeat(4, 1fr);
  }
  @include _640 {
    grid-template-columns: repeat(3, 1fr);
  }
  @include _480 {
    grid-template-columns: repeat(2, 1fr);
  }
}
</style>
