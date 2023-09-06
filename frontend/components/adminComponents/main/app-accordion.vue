<template>
  <div class="accordion" :class="propsData.content_position">
    <h2 v-if="propsData.title">{{ propsData.title }}</h2>
    <div class="accordion-wrapper">
      <div
        v-for="(accItem, idx) in propsData.list"
        :key="'accItem' + idx"
        class="accordion-item"
      >
        <div class="accordion-trigger" @click="slideToggle($event.target)">
          {{ accItem.title }}
        </div>
        <div class="accordion-content" hidden v-html="accItem.text"></div>
      </div>
    </div>
  </div>
</template>

<script>
import slideMixin from '~/mixins/slideMixin'
export default {
  name: 'AppAccordion',
  mixins: [slideMixin],
}
</script>

<style lang="scss" scoped>
.accordion {
  display: flex;
  flex-direction: column;
  &.left{
    align-items: flex-start;
  }
  &.right{
    align-items: flex-end;
  }
  &-wrapper {
    width: 60%;
    @include md{
      width: 100%;
    }
  }
  h2{
    width: 100%;
  }
  &-item {
  }
  &-trigger {
    padding: 30px 0px;
    cursor: pointer;
    font-size: 22px;
    font-style: normal;
    font-weight: 600;
    line-height: 130%;
    border-top: 2px solid #e3e3e3;
    transition: 0.5s;
    &.active {
      border-color: #000;
    }
  }
  &-content::v-deep {
    > *:first-child {
      margin-top: 0;
    }
    > *:last-child {
      margin-bottom: 0;
      padding-bottom: 30px;
    }
  }
}
</style>
