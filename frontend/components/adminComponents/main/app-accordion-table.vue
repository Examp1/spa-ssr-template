<template>
  <div class="accordion-table">
    <h2 v-if="propsData.title">{{ propsData.title }}</h2>
    <div
      v-for="(accItem, idx) in propsData.list"
      :key="'accItem' + idx"
      class="accordion-itemWrp"
    >
      <div class="accordion-item" @click.self="slideToggle($event.target)">
        <div class="titleWrp">
          <span :class="accItem.icon" class="icon"></span>
          <p class="title">{{ accItem.title }}</p>
        </div>
        <p class="date">{{ accItem.date }}</p>
        <div class="subTitle" v-html="accItem.subtitle"></div>
        <div
          v-if="accItem.text && accItem.text !== '<p><br></p>'"
          class="accordion-trigger"
        >
          +
        </div>
      </div>
      <div hidden class="accordion-content redactor" v-html="accItem.text"></div>
    </div>
  </div>
</template>

<script>
import slideMixin from '~/mixins/slideMixin'
export default {
  name: 'AccordionTable',
  mixins: [slideMixin]
}
</script>

<style lang="scss" scoped>
</style>
<style lang="scss" scoped>
.accordion-itemWrp {
  border-top: 1px solid #cbd8fb;
  border-bottom: 1px solid #cbd8fb;
  margin-bottom: -1px;
}
.accordion-item {
  transition: 0.3s;
  padding: 20px 15px 20px 5px;
  cursor: pointer;
  display: grid;
  grid-template-columns: 2fr 140px 3fr 30px;
  justify-content: space-between;
  align-items: center;
  color: #000;
  grid-gap: 10px;
  @media (max-width: 576px) {
    grid-template-columns: 1fr;
    position: relative;
  }

  &:hover {
    background-color: #e7edf1;
  }
  &.active {
    .accordion-trigger {
      transform: rotate(45deg);
      background-color: #e5ecf1;
    }
  }
  * {
    pointer-events: none;
    margin: 0;
  }
  .titleWrp {
    display: flex;
    align-items: center;
    @media (max-width: 768px) {
      flex-direction: column;
      align-items: unset;
      .icon {
        margin-bottom: 10px;
      }
    }
    .icon {
      color: #d92219;
      font-size: 45px;
      margin-right: 20px;
    }
  }
  .title {
    font-weight: 500;
    font-size: 22px;
    line-height: 110%;
    letter-spacing: -0.02em;
  }
  .date {
    font-weight: 400;
    font-size: 17px;
    line-height: 110%;
    letter-spacing: -0.02em;
  }
  .subTitle {
    font-weight: 500;
    font-size: 17px;
    line-height: 125%;
    letter-spacing: -0.02em;
  }
  .accordion-trigger {
    // transform-origin: 50%;
    transition: 0.3s;
    cursor: pointer;
    width: 30px;
    line-height: 100%;
    height: 30px;
    font-weight: 600;
    border: 1px solid $primary;
    display: flex;
    align-items: center;
    border-radius: 50%;
    justify-content: center;
    @media (max-width: 576px) {
      position: absolute;
      right: 0;
      top: 10px;
    }
  }
}
.accordion-content::v-deep {
  max-width: 1055px;
  padding: 25px;
  @media (max-width: 576px) {
    padding-left: 15px;
  }
  p,
  span,
  ul,
  ol {
    padding: 15px 0px;
  }
  * {
    margin: 0;
    font-weight: 400;
    font-size: 18px;
    line-height: 145%;
    letter-spacing: -0.02em;
    color: #000;
  }
  ul,
  ol {
    @media (max-width: 576px) {
      padding-left: 0;
    }
    li:not(:last-of-type) {
      padding-bottom: 15px;
    }
  }
}
.icon-06 {
  color: #162b3f !important;
}
</style>

