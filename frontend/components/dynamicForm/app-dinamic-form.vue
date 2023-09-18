<template>
  <ValidationObserver
    ref="formObserver"
    v-slot="{ handleSubmit }"
    tag="div"
    class="form container"
  >
    <form @submit.prevent="handleSubmit(submitForm)">
      <component
        :is="componentMappings[field.type]"
        v-for="(field, idx) in propsData.form_data || propsData.list"
        :key="'field' + idx"
        :props-data="field"
        :validation-rules="generateValidationRules(field)"
        @input="handleInput"
      ></component>
      <button class="submitBtn">Отправить</button>
    </form>
  </ValidationObserver>
</template>

<script>
import { submitForm } from '~/services/formService'
export default {
  name: 'DinamicForm',
  data() {
    return {
      inputData: {},
    }
  },

  computed: {
    componentMappings() {
      return {
        'form-input': () => import('./fields/form-input.vue'),
        'form-text': () => import('./fields/form-text.vue'),
        'form-title': () => import('./fields/form-title.vue'),
        'form-editor': () => import('./fields/form-editor.vue'),
        'form-select': () => import('./fields/form-select.vue'),
        'form-checkbox': () => import('./fields/form-checkbox.vue'),
      }
    },
  },
  methods: {
    generateValidationRules(field) {
      if (!field.rules) return

      const ruleKeys = ['required', 'email', 'min', 'max']
      const rules = {}

      ruleKeys.forEach((key) => {
        if (field.rules[key]) {
          rules[key] = true
        }
      })

      return rules
    },
    handleInput(value, name) {
      this.$set(this.inputData, name, value)
    },
    reset() {
      this.$refs.formObserver.reset()
      this.$refs.formObserver.$children.forEach((el) => {
        el.value = ''
      })
    },
    async submitForm() {
      const isValid = await this.$refs.formObserver.validate()
      if (!isValid) return

      // const token = await this.$recaptcha.execute('login')
      const option = Object.assign(this.inputData, {
        form_id: this.propsData.form_id,
        // 'g-recaptcha-response': token,
      })

      const response = await submitForm(this.$axios, option)

      if (response.success) {
        this.success = true
        this.successText = response.data
        this.reset()

        setTimeout(() => {
          this.success = false
        }, 4000)
      } else {
        console.warn(response.error)
      }
    },
  },
}
</script>

<style lang="scss" scoped src="./form.scss">
</style>

<style lang="scss" scoped>
.submitBtn {
  padding: 15px 44px 15px 15px;
}
</style>
