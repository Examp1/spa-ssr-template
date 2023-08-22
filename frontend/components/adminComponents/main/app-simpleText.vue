<template>
  <div>
    <h2 v-if="propsData.title" v-html="propsData.title"></h2>
    <div v-if="propsData.description" v-html="propsData.description"></div>
  </div>
</template>

<script>
export default {
  name: "ArticleSimpleText",
};
</script>

<style lang="scss" scoped>
</style>
