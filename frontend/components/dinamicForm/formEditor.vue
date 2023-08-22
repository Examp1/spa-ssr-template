<template>
  <div class="formInput">
    <div class="inner">
      <label class="label">{{ propsData.title }}</label>
      <span v-if="propsData.rules.max" class="maxSymbols">{{
        $t('validations.charactersMax', { maxSymbol: maxSymbols })
      }}</span>
    </div>
    <textarea
      type="text"
      :name="propsData.name"
      :placeholder="propsData.placeholder"
      :class="{ error: inputData.$invalid && showError }"
      @input="setTextData"
    ></textarea>
    <span v-if="inputData.$invalid && showError" class="error">
      <!-- {{ errorText }} -->
      <span v-if="!inputData.required">{{ messages.required }}</span>
      <span v-if="!inputData.email">{{ messages.email }}</span>
      <span v-if="!inputData.minLength">{{ messages.min }}</span>
      <span v-if="!inputData.maxLength">{{ messages.max }}</span>
    </span>
  </div>
</template>

<script>
export default {
  props: {
    inputData: {
      type: Object,
      default: () => {},
    },
    messages: {
      type: Object,
      default: () => {},
    },
    showError: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      maxSymbols: this.propsData.rules.max || null,
    }
  },
  computed: {
    errorText() {
      if (!this.inputData.email) {
        return this.messages.email
      }
      if (!this.inputData.required) {
        return this.messages.required
      }
      if (!this.inputData.maxLength) {
        return this.messages.min
      }
      if (!this.inputData.maxLength) {
        return this.messages.max
      }
      return null
    },
  },
  methods: {
    maxSymbolCheck(value) {
      const max = this.propsData.rules.max
      if (value.length < max) {
        this.maxSymbols = max - value.length
      } else if (value.length > max) {
        this.maxSymbols = 0
      } else {
        this.maxSymbols = max
      }
    },
    setTextData(event) {
      this.maxSymbolCheck(event.target.value)
      this.$emit('input', event.target.value)
    },
  },
}
</script>

<style lang="scss" scoped>
.inner{
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.maxSymbols {
  color: var(--grey-400, #606060);
  font-family: Open Sans;
  font-size: 12px;
  font-style: normal;
  font-weight: 400;
  line-height: normal;
}
</style>
