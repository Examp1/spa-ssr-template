<template>
  <ValidationObserver v-slot="{ invalid }">
    {{ invalid }}
    <div class="blockForm container">
      <component
        :is="componentMappings[field.type]"
        v-for="(field, idx) in inputs"
        :key="'field' + idx"
        :props-data="field"
        @input="handleInput"
      ></component>
      <button :disabled="invalid" @click="submitForm">Отправить</button>
    </div>
  </ValidationObserver>
</template>

<script>
export default {
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
          type: 'form-editor',
          name: 'textarea',
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
      }
    },
  },
  methods: {
    handleInput(value, name) {
      this.$set(this.inputData, name, value)
    },
    submitForm() {
      console.log(this.invalid)
      // if (this.invalid) return
    },
  },
}
</script>

<style lang="scss" scoped>
</style>
