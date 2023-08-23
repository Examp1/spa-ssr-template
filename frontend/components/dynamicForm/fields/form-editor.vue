<template>
  <div class="formInput">
    <label class="label">{{ propsData.title }}</label>
    <ValidationProvider
      :name="propsData.name"
      :rules="validationRules"
      v-slot="{ errors }"
    >
      <textarea
        v-model="inputValue"
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
  data() {
    return {
      inputValue: '',
    }
  },
  computed: {
    validationRules() {
      const rules = {}
      if (this.propsData.rules.required) rules.required = true
      if (this.propsData.rules.email) rules.email = true
      if (this.propsData.rules.min) rules.min = this.propsData.rules.min
      if (this.propsData.rules.max) rules.max = this.propsData.rules.max
      return rules
    },
  },
}
</script>
