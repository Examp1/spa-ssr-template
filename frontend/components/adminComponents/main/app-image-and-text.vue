<template>
  <section class="image-and-text">
    <h2>{{ propsData.title }}</h2>
    <div class="wrp" :class="propsData.image_position">
      <div class="text">
        <div v-html="propsData.description"></div>
        <div>
          <app-btn
            v-for="(btn, idx) in propsData.btns"
            :key="'btn' + idx"
            :props-data="btn"
          ></app-btn>
        </div>
      </div>
      <div class="imageWrapper">
        <source
          media="(max-width: 768px)"
          :srcset="path(propsData.image_mob)"
        />
        <img :src="path(propsData.image)" alt="" />
      </div>
    </div>
  </section>
</template>

<script>
import AppBtn from '../../ui/app-btn.vue'

export default {
  name: 'image-and-text',
  components: { AppBtn },
}
</script>

<style lang="scss" scoped>
.image-and-text .wrp {
  display: flex;
  justify-content: space-between;
  h2 {
    font-weight: 500;
    font-size: 22px;
    line-height: 110%;
  }
  &.left {
    flex-direction: row-reverse;
  }
  @media (max-width: 576px) {
    flex-direction: column-reverse !important;
  }
}
.text,
.imageWrapper {
  width: calc(50% - 15px);
  @media (max-width: 576px) {
    width: 100%;
  }
  img {
    width: 100%;
    position: sticky;
    top: 0px;
  }
}
.imageWrapper {
  @media (max-width: 576px) {
    margin-bottom: 30px;
  }
}
</style>
