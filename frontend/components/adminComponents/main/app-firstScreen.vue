<template>
  <div class="firstScreen">
    <img v-if="hasBg" class="bgImage" :src="path(propsData.image)" alt="bg" />
    <div class="container">
      <img v-if="propsData.sticker" class="sticker" :src="path(propsData.sticker)" alt="sticker" />
      <div class="inner-wrapper" :class="contentAlign">
        <div class="text-wrapper">
          <h1>{{ propsData.title }}</h1>
          <div v-html="propsData.text"></div>
          <app-btns :props-data="propsData.btns"></app-btns>
        </div>
        <!-- <img v-if="!hasBg" :src="path(propsData.image)" alt="img" /> -->
      </div>
    </div>
  </div>
</template>

<script>
import AppBtns from '../../ui/app-btns.vue'
export default {
  name: 'FirstScreen',
  components: { AppBtns },
  computed: {
    hasBg() {
      return this.propsData.with_fon === '0'
    },
    contentAlign() {
      switch (this.propsData.widget_type) {
        case 'type_1':
          return 'left'
        case 'type_2':
          return 'center'
        case 'type_3':
          return 'right'
        default:
          return 'left'
      }
    },
  },
}
</script>

<style lang="scss" scoped>
.firstScreen {
  position: relative;
  z-index: 1;
}
.bgImage {
  top: 0;
  left: 0;
  position: absolute;
  width: 100%;
  height: 100%;
  z-index: -1;
  object-fit: cover;
}
.container {
  min-height: calc(100vh - 75px);
  position: relative;
  display: flex;
  align-items: center;
  justify-content: space-between;
  .inner-wrapper {
    display: flex;
    grid-gap: 20px;
    &.left {
      flex-direction: row;
    }
    &.center {
      justify-content: center;
    }
    &.right {
      flex-direction: row-reverse;
    }
  }
  // flex-direction: ;
}
.sticker {
  position: absolute;
  bottom: 0;
  right: 0;
}
</style>
