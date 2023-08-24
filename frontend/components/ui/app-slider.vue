<template>
  <VueSlickCarousel
    v-bind="propsData"
    class="globalSlider container-fluid"
    :class="{ '--swiping': swiping === true }"
    @swipe="setCarouselSwiping(true)"
    @mouseup.native="setCarouselSwiping(false)"
    @touchend.native="setCarouselSwiping(false)"
  >
    <slot />
  </VueSlickCarousel>
</template>

<script>
import VueSlickCarousel from 'vue-slick-carousel'
export default {
  name: 'SliderComponent',
  components: { VueSlickCarousel },
  data() {
    return {
      swiping: false,
    }
  },
  methods: {
    setCarouselSwiping(state) {
      this.swiping = state
    },
  },
}
</script>

<style lang="scss">
.slick-slider.--swiping {
  .slick-list:after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
  }
}
</style>
