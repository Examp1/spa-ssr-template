<template>
  <div class="formInput contactAgree">
    <!-- <div class="formInput agree contactAgree" :class="{'active': value}">
    <input
      :id="`${propsData.name}${type}`"
      v-model="value"
      type="checkbox"
      @click="change"
    />
    <label
      :class="{error: inputData.$invalid && showError}"
      :for="`${propsData.name}${type}`"
      v-html="propsData.title"
    ></label> -->
    <div
      :id="`${propsData.name}${type}`"
      class="agree"
      :class="{ active: value }"
      @click="change"
      v-html="propsData.title"
    ></div>
  </div>
</template>

<script>
export default {
  props: {
    inputData: {
      type: Object,
      default: () => {},
    },
    type: {
      type: String,
      default: '',
    },
    showError: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      value: false,
    }
  },
  methods: {
    change() {
      this.value = !this.value
      const a = JSON.stringify(this.value)
      this.$set(this, 'value', JSON.parse(a))
      this.$emit('input', Boolean(a))
    },
  },
}
</script>

<style lang="scss" scoped>
.contactAgree {
  display: flex;
  align-items: center;
  label {
    cursor: pointer;
  }
  [type='checkbox'] {
    opacity: 0;
    visibility: hidden;
    position: absolute;
  }
}
</style>
