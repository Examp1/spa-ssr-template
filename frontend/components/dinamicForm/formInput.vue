<template>
  <div class="formInput">
    <label class="label">{{ propsData.title }}</label>
    <input
      v-if="propsData.name !== 'tel'"
      type="text"
      :name="propsData.name"
      :class="{ error: inputData.$invalid && showError }"
      :placeholder="propsData.placeholder"
      @input="$emit('input', $event.target.value)"
    />
    <input
      v-else
      v-mask="'+38##########'"
      type="phone"
      :name="propsData.name"
      :class="{ error: inputData.$invalid && showError }"
      :placeholder="propsData.placeholder"
      @input="$emit('input', $event.target.value)"
    />
    <span v-if="inputData.$invalid && showError" class="error">
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
    messages: {
      type: Object,
      default: () => {},
    },
    inputData: {
      type: Object,
      default: () => {},
    },
    showError: {
      type: Boolean,
      default: false,
    },
  },
  computed: {
    errorText() {
      if (!this.inputData.email) {
        return this.messages.email
      }
      if (!this.inputData.required) {
        return this.messages.required
      }
      if (!this.inputData.min) {
        return this.messages.min
      }
      if (!this.inputData.max) {
        return this.messages.max
      }
      return null
    },
  },
}
</script>

<style lang="scss" scoped>
</style>
