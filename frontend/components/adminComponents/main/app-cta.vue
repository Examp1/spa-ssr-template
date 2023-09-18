<template>
  <div class="cta">
    <div class="container" :style="containerBg">
      <h2 v-if="propsData.title" v-html="propsData.title"></h2>
      <div class="redactor" v-html="propsData.text"></div>
      <app-btns :props-data="propsData.btns"></app-btns>
    </div>
  </div>
</template>

<script>
import appBtns from '../../ui/app-btns.vue'
export default {
  name: 'AppCta',
  components: { appBtns },
  computed: {
    containerBg() {
      return `background-color: ${this.propsData.bg_color}`
    },
  },
}
</script>

<style lang="scss" scoped>
.cta {
  .container {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 50px 0px;
    border-radius: 22px;
  }
}
</style>
