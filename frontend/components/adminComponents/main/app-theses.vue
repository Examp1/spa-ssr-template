<template>
  <div class="theses">
    <div v-for="(item, idx) in propsData.list" :key="idx" class="theses-item">
      {{ item.item }}
    </div>
  </div>
</template>

<script>
  export default {
    name: 'AppTheses'
  }
</script>

<style lang="scss" scoped>
.theses{
  display: flex;
  flex-wrap: wrap;
  grid-gap: 5px;
  &-item{
    cursor: pointer;
    padding: 10px;
    border: 1px solid #CBCBCB;
  }
}
</style>
