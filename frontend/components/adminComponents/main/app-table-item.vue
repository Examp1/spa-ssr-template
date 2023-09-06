<template>
  <div class="table-item">
    <div
      v-for="(item, idx) in propsData"
      :key="'item' + idx"
      :style="{ 'min-width': item.column_width }"
      v-html="item.column_text"
    ></div>
  </div>
</template>

<script>
export default {
  name: 'AppTableItem',
}
</script>

<style lang="scss" scoped>
.table-item{
  display: flex;
  grid-gap: 10px;
}
</style>
