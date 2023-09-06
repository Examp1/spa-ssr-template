<template>
  <ValidationObserver v-slot="{ invalid }" tag="form" class="form container">
      <component
        :is="componentMappings[field.type]"
        v-for="(field, idx) in propsData.form_data || propsData.list"
        :key="'field' + idx"
        :props-data="field"
        :validation-rules="generateValidationRules(field)"
        @input="handleInput"
      ></component>
      <button :disabled="invalid" class="submitBtn" @click="submitForm">
        Отправить
      </button>
  </ValidationObserver>
</template>

<script>
export default {
  name: 'DinamicForm',
  data() {
    return {
      inputData: {},
      inputs: [
        {
          visibility: '1',
          type: 'form-title',
          value: 'Залиште ваші контакти у формі нижче',
        },
        {
          visibility: '1',
          type: 'form-text',
          value:
            'Ми зв’яжемось з вами у найкоротші строки для відповіді на всі питання',
        },
        {
          visibility: '1',
          type: 'form-input',
          name: 'name',
          title: null,
          placeholder: 'П.І.Б.',
          show_in_message: '1',
          shown_name: null,
          rules: { required: true, email: false, min: null, max: null },
          messages: {
            required: "Це обов'язкове поле",
            email: null,
            min: null,
            max: null,
          },
        },
        {
          visibility: '1',
          type: 'form-input',
          name: 'company',
          title: null,
          placeholder: 'Назва компанії',
          show_in_message: '1',
          shown_name: null,
          rules: { required: true, email: false, min: null, max: null },
          messages: {
            required: "Це обов'язкове поле",
            email: null,
            min: null,
            max: null,
          },
        },
        {
          visibility: '1',
          type: 'form-input',
          name: 'position',
          title: null,
          placeholder: 'Посада',
          show_in_message: '1',
          shown_name: null,
          rules: { required: false, email: false, min: null, max: null },
          messages: {
            required: "Це обов'язкове поле",
            email: null,
            min: null,
            max: null,
          },
        },
        {
          visibility: '1',
          type: 'form-input',
          name: 'email',
          title: null,
          placeholder: 'Ваш Email',
          show_in_message: '1',
          shown_name: null,
          rules: { required: true, email: true, min: null, max: null },
          messages: {
            required: "Це обов'язкове поле",
            email: null,
            min: null,
            max: null,
          },
        },
        {
          visibility: '1',
          type: 'form-input',
          name: 'phone',
          title: null,
          placeholder: 'Телефон',
          show_in_message: '1',
          shown_name: null,
          rules: { required: true, email: false, min: null, max: null },
          messages: { required: null, email: null, min: null, max: null },
        },
        {
          visibility: '1',
          type: 'form-editor',
          name: 'text_for_form',
          title: null,
          placeholder: null,
          show_in_message: '1',
          shown_name: null,
          rules: { required: false, email: false, min: null, max: null },
          messages: { required: null, email: null, min: null, max: null },
        },
        {
          visibility: '1',
          type: 'form-checkbox',
          name: 'agreement',
          title:
            'Я прочитав і приймаю <a href="https://owlsitefrombox.owlweb.com.ua/privacy-policy" target="_blank">Політику конфіденційонсті</a><br> умови використання та правила спільноти.',
          show_in_message: '1',
          shown_name: '<b>Погодитись</b>',
          rules: { required: true, email: false, min: null, max: null },
          messages: { required: null, email: null, min: null, max: null },
        },
        {
          visibility: '1',
          type: 'form-select',
          name: 'select',
          title: 'select',
          options: { red: ' red', green: ' green', gray: ' gray' },
          show_in_message: '1',
          shown_name: null,
          rules: { required: true, email: false, min: null, max: null },
          messages: { required: null, email: null, min: null, max: null },
        },
      ],
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
    async submitForm() {
      if ( this.invalid ) return
      const token = await this.$recaptcha.execute('login')
      const option = Object.assign(this.InputValues, {
        form_id: this.formId || this.propsData.form_id,
        'g-recaptcha-response': token,
      })
      this.showError = true
      if (!this.$v.$invalid) {
        this.$axios.$post('/api/request/send', option).then((res) => {
          this.success = true
          this.successText = res.data
          this.$refs.blockForm.reset()
          setTimeout(() => {
            this.success = false
          }, 4000)
        })
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
