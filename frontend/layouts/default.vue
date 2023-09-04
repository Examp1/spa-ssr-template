<template>
  <div class="app">
    <the-header></the-header>
    <nuxt/>
    <background-layer></background-layer>
  </div>
</template>

<script>
import TheHeader from '../components/navigations/the-header.vue'
import BackgroundLayer from '../components/ui/BackgroundLayer.vue'
export default {
  components: { BackgroundLayer, TheHeader },}
</script>

<style lang="scss" scoped>
</style>
