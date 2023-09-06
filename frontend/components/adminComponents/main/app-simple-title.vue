<template>
  <div class="simple-title">
    <span class="subtitle">{{ propsData.subtitle }}</span>
    <div class="wrp">
      <h2 v-if="propsData.title">{{ propsData.title }}</h2>
      <app-btns :props-data="propsData.btns"></app-btns>
    </div>
  </div>
</template>

<script>
import AppBtns from '../../ui/app-btns.vue'
export default {
  name: 'SimpleTitle',
  components: {
    AppBtns,
  },
}
</script>

<style lang="scss" scoped>
.wrp {
  display: flex;
  justify-content: space-between;
  align-items: center;
  h2 {
    font-size: 40px;
    font-style: normal;
    font-weight: 500;
    line-height: 135%;
    // letter-spacing: -5.973px;
    text-transform: uppercase;
    margin-bottom: 0;
  }
}
</style>
