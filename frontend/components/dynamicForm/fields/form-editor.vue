<template>
  <div class="formField">
    <label class="label">{{ propsData.title }}</label>
    <ValidationProvider
      :name="propsData.name"
      :rules="validationRules"
      v-slot="{ errors }"
    >
      <textarea
        v-model="value"
        type="text"
        :name="propsData.name"
        :class="{ error: errors[0] }"
        :placeholder="propsData.placeholder"
        @input="$emit('input', $event.target.value, propsData.name)"
      />
      <span v-if="errors[0]" class="error">{{ errors[0] }}</span>
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
      value: '',
    }
  },
}
</script>
