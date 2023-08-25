<template>
  <div class="btns">
    <app-btn v-for="(btn, idx) in propsData" :key="'btn' +idx" :props-data="btn"></app-btn>
  </div>
</template>

<script>
import appBtn from './app-btn.vue'
  export default {
  components: { appBtn },

  }
</script>

<style lang="scss" scoped>
.btns{
  display: flex;
  grid-gap: 20px;
  @include large-phones{
    flex-wrap: wrap;
  }
}
</style>
