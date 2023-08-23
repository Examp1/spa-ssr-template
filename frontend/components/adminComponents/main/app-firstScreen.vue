<template>
  <section class="firstScreen">
    <img v-if="hasBg" class="bgImage" :src="path(propsData.image)" alt="" />
    <div class="container" :class="contentAlign">
      <img class="sticker" :src="path(propsData.sticker)" alt="sticker" />
      <div class="textWrapper">
        <h1>{{ propsData.title }}</h1>
        <div v-html="propsData.text"></div>
        <app-btn
          v-for="(btn, idx) in propsData.btns"
          :key="'btn' + idx"
          :props-data="btn"
        ></app-btn>
      </div>
      <img v-if="!hasBg" :src="path(propsData.image)" alt="">
    </div>
  </section>
</template>

<script>
import appBtn from '../../ui/app-btn.vue'
export default {
  name: 'FirstScreen',
  components: { appBtn },
  // XXX
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
  min-height: 87.5vh;
  position: relative;
  display: flex;
  justify-content: space-between;
  // flex-direction: ;
  &.left {
    align-items: flex-start;
  }
  &.center {
    align-items: center;
  }
  &.right {
    align-items: flex-end;
  }
}
.sticker {
  position: absolute;
  bottom: 0;
  right: 0;
}
</style>
