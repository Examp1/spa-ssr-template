<template>
  <div ref="select" class="formField">
    <label v-if="propsData.title" class="label">{{ propsData.title }}</label>
    <ValidationProvider
      v-slot="{ errors }"
      :name="propsData.name"
      :rules="validationRules"
    >
      <input v-model="value" hidden type="text" />
      <div class="selected" :class="{ open: open }" @click="open = !open">
        {{ value || '---' }}
      </div>
      <div class="options" :class="{ open: open }">
        <p
          v-for="(opt, idx) in propsData.options"
          :key="idx"
          @click="setOpt(opt)"
        >
          {{ opt }}
        </p>
      </div>
      <span v-if="errors[0]" class="error">
        {{ errors[0] }}
      </span>
    </ValidationProvider>
  </div>
</template>

<script>
export default {
  props: {
    validationRules: {
      type: Object,
      default: () => {},
    },
  },
  data() {
    return {
      open: false,
      value: null,
    }
  },
  methods: {
    setOpt(opt) {
      this.value = opt
      this.open = false
      this.$emit('input', opt, this.propsData.name) // Emit the selected value to the parent
    },
  },
}
</script>
<style lang="scss" scoped>
.selected {
  display: flex;
  align-items: center;
  padding: 10px 13px;
  border: 1px solid var(--1-gray-1, #c8c8cf);
  width: 100%;
  margin-bottom: 20px;
  outline: none;
  transition: 0.3s;
  position: relative;
}
// #0E273D
.formField {
  position: relative;
  border-bottom: none !important;
  margin-bottom: 30px;
  &::before {
    display: none !important;
  }
}
.options {
  position: absolute;
  top: 70px;
  height: 200px;
  overflow-y: scroll;
  width: 100%;
  left: 0;
  z-index: 1;
  opacity: 0;
  transform: translateY(-100%);
  visibility: hidden;
  background: #fff;
  border: 1px solid rgba(53, 53, 53, 0.14);
  &.open {
    opacity: 1;
    transform: translateY(0);
    visibility: visible;
  }
  p {
    padding: 10px 15px;
    transition: 0.3s;
    cursor: pointer;
    margin: 0;
    width: 100%;
    &:not(:last-of-type) {
      border-bottom: 1px solid rgba(53, 53, 53, 0.14);
    }
    &:hover {
      background-color: #dfe8ee;
      color: #164e77;
    }
  }
}
</style>
