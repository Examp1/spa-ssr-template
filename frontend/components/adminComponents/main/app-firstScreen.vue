<template>
  <div class="firstScreen">
    <img class="bgImage" :src="path(propsData.image)" alt="" />
    <div class="container" :class="contentAlign">
      <img class="sticker" :src="path(propsData.sticker)" alt="sticker" />
      <h1>{{ propsData.title }}</h1>
      <div v-html="propsData.text"></div>
      <app-btn
        v-for="(btn, idx) in propsData.btns"
        :key="'btn' + idx"
        :props-data="btn"
      ></app-btn>
    </div>
  </div>
</template>

<script>
import appBtn from '../../ui/app-btn.vue'
export default {
  name: 'FirstScreen',
  components: { appBtn },
  // XXX
  computed: {
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
  justify-content: center;
  flex-direction: column;
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
  top: 0;
  right: 0;
}
</style>
