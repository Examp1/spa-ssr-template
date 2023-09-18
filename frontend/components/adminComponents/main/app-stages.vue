<template>
  <div class="app-stages" :class="propsData.content_position">
    <div class="stage-wrp">
      <h2 v-if="propsData.title">{{ propsData.title }}</h2>
      <div class="stages-list">
        <div
          v-for="(li, idx) in propsData.list"
          :key="'li' + idx"
          class="stages-item"
        >
          <div class="stage-counter">{{ (idx + 1) | zeroPad }}</div>
          <div class="stage-text">
            <h3>{{ li.title }}</h3>
            <div class="redactor"  v-html="li.text"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AppStages',
}
</script>

<style lang="scss" scoped>
.app-stages {
  display: flex;
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
.stages-list {
  .stages-item {
    --gutter: 60px;
    position: relative;
    padding-left: calc(78px + var(--gutter));
    .stage-text {
      h3 {
        font-size: 27px;
        font-style: normal;
        font-weight: 600;
        line-height: 120%;
        margin-bottom: 15px;
      }
      div::v-deep > * {
        margin-top: 0;
        color: var(--gray-gray-700, #363636);
        font-size: 20px;
        font-style: normal;
        font-weight: 400;
        line-height: 145%; /* 29px */
        letter-spacing: -0.4px;
      }
    }
    .stage-counter {
      width: 78px;
      height: 78px;
      border: 1px solid #000;
      display: flex;
      justify-content: center;
      align-items: center;
      text-align: center;
      font-size: 30px;
      font-style: normal;
      font-weight: 400;
      line-height: 120%;
      position: absolute;
      left: 0;
      &::before {
        content: '';
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        background-color: #000;
      }
    }
  }
}
</style>
