<template>
  <div class="full-image">
    <h2>{{ propsData.title }}</h2>
    <img :src="path(propsData.image)" alt="full-image">
  </div>
</template>

<script>
  export default {
    name: 'FullImage'
  }
</script>

<style lang="scss" scoped>

</style>
