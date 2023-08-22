<template>
  <div>
    <nuxt class="app" />
    <background-layer></background-layer>
  </div>
</template>

<script>
import BackgroundLayer from '../components/ui/BackgroundLayer.vue'
export default {
  components: { BackgroundLayer },}
</script>

<style lang="scss" scoped>
</style>
