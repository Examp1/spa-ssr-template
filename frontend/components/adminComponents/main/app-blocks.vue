<template>
  <div class="blocks">
    <h2>{{ propsData.title }}</h2>
    <div
      class="blocks-wrapper"
      :style="cssVariables"
    >
      <div
        v-for="(block, idx) in propsData.list"
        :key="'block' + idx"
        class="block"
      >
        <h3 class="block-title">{{ block.title }}</h3>
        <img :src="path(block.image)" :alt="'block' + idx" />
        <div class="redactor" v-html="block.text"></div>
        <p>кнопки нужно свести к 1м названиям в апи</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AppBlocks',
  computed: {
    cssVariables() {
      let columnGap = "30px";
      const rowGap = "90px";
      if (+this.propsData.title_column_select === 2) {
        columnGap = "60px";
      } else if ([3, 4].includes(+this.propsData.title_column_select)) {
        columnGap = "30px";
      }
      return {
        '--column': this.propsData.title_column_select,
        'column-gap': columnGap,
        'row-gap': rowGap
      };
    }
  }
}
</script>

<style lang="scss" scoped>
.blocks-wrapper {
  display: grid;
  grid-template-columns: repeat(var(--column), 1fr);
  @include lg {
    grid-template-columns: repeat(calc(min(var(--column), 4) - 1), 1fr);
  }

  @include md {
    grid-template-columns: repeat(calc(min(var(--column), 3) - 1), 1fr);
  }

  @include sm {
    grid-template-columns: 1fr;
  }
}
</style>
