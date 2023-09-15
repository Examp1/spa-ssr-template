<template>
  <div class="app">
    <the-header></the-header>
    <nuxt />
    <background-layer></background-layer>
  </div>
</template>

<script>
import Lenis from '@studio-freight/lenis'
import TheHeader from '../components/navigations/the-header.vue'
import BackgroundLayer from '../components/ui/BackgroundLayer.vue'
export default {
  components: { BackgroundLayer, TheHeader },
  mounted() {
    const lenis = new Lenis()

    lenis.on('scroll', (e) => {
      console.log(e)
    })

    function raf(time) {
      lenis.raf(time)
      requestAnimationFrame(raf)
    }

    requestAnimationFrame(raf)
  },
}
</script>

<style lang="scss" scoped>
</style>
