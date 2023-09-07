<template>
  <div class="image-and-text">
    <h2 v-if="propsData.title">{{ propsData.title }}</h2>
    <div
      class="wrp"
      :class="propsData.image_position"
      :style="{
        '--imgWidth': calculateTextAndImageWidth.image,
        '--textWidth': calculateTextAndImageWidth.text,
      }"
    >
      <div class="textWrapper">
        <div v-html="propsData.description"></div>
        <div>
          <app-btns :props-data="propsData.btns"></app-btns>
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
  </div>
</template>

<script>
import AppBtns from '../../ui/app-btns.vue'

export default {
  name: 'ImageAndText',
  components: { AppBtns },
  computed: {
    calculateTextAndImageWidth() {
      return {
        text: (10 - this.propsData.column_width) * 10 + '%',
        image: this.propsData.column_width * 10 + '%',
      }
    },
  },
}
</script>

<style lang="scss" scoped>
.image-and-text {
  .wrp {
    display: flex;
    grid-gap: 60px;
    @include sm{
      flex-direction: column;
    }
    .textWrapper{
      width: var(--textWidth);
      @include sm {
        width: 100%;
      }
    }
    .imageWrapper {
      width: var(--imgWidth);
      img{
        width: 100%;
      }
      @include sm {
        width: 100%;
      }
    }
    &.left{
      flex-direction: row-reverse;
    }
  }
}
</style>
