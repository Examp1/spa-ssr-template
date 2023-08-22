<template>
  <section class="callback dinamicForm" v-if="propsData">
    <div class="callback__container container">
      <div class="callback__body">
        <form-title v-if="formTitle" :props-data="formTitle" class="h2"></form-title>
        <form
          id="form-question"
          ref="blockForm"
          class="callback__form form block-content"
        >
          <template v-for="(item, idx) in propsData.list">
            <component
              :is="item.type"
              v-if="item.type !== 'form-title'"
              :key="idx"
              v-model="InputValues[item.name]"
              :props-data="item"
              :type="'block'"
              :input-data="$v.InputValues[item.name]"
              :messages="item.messages"
              :show-error="showError"
              @changeChekbox="changeChekbox"
            ></component>
          </template>
          <span class="form__button button" @click="sendForm"
            >{{ propsData.btn_name }} <span class="icon icon-send"></span
          ></span>
        </form>
        <div
          v-if="success"
          class="success modal"
          @mousedown.self="success = false"
        >
          <div class="modalContent">
            <i class="icon icon-close close" @click="success = false"></i>
            <p>Дякуємо за звернення, ваше повідомлення надіслано</p>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import { required, email, minLength, maxLength } from '@vuelidate/validators'
import { mapGetters } from 'vuex'
import formText from '../dinamicForm/formText.vue'
import formInput from '../dinamicForm/formInput.vue'
import formTitle from '../dinamicForm/formTitle.vue'
import FormEditor from '../dinamicForm/formEditor.vue'
import FormCheckbox from '../dinamicForm/formCheckbox.vue'
export default {
  name: 'FormBlock',
  components: { formInput, formText, formTitle, FormEditor, FormCheckbox },
  props: {
    custom: {
      type: String,
      default: '',
    },
  },
  data() {
    return {
      InputValues: {},
      showError: false,
      success: false,
      successText: null,
    }
  },
  computed: {
    ...mapGetters({
      getMenuData: 'modules/form/getMenuData',
      getFormId: 'modules/form/getFormId',
    }),
    formTitle() {
      let temp = null
      this.propsData?.list.forEach((el) => {
        if (el.type === 'form-title') temp = el
      })
      return temp
    },
  },
  validations() {
    const temp = {}
    temp.InputValues = {}
    this.propsData?.list.forEach((el) => {
      temp.InputValues[el.name] = {}
      //   }
      this.propsData?.list.forEach((el) => {
        if (el.rules) {
          let rules = {}
          if (el.rules.required) {
            rules = { ...rules, ...{ required } }
          }
          if (el.rules.email) {
            rules = { ...rules, ...{ email } }
          }
          if (el.rules.min) {
            rules = { ...rules, ...{ minLength: minLength(el.rules.min) } }
          }
          if (el.rules.max) {
            rules = { ...rules, ...{ maxLength: maxLength(el.rules.max) } }
          }
          temp.InputValues[el.name] = {
            ...rules,
          }
        }
      })
    })

    return temp
  },
  mounted() {
    if (this.propsData) {
      this.propsData?.list.forEach((el) => {
        this.$set(this.InputValues, el.name, '')
      })
    }
  },
  methods: {
    changeChekbox(name, data) {
      this[name] = data
    },
    async sendForm() {
      const token = await this.$recaptcha.execute('login')
      const option = Object.assign(this.InputValues, {
        form_id: this.formId || this.propsData.form_id,
        'g-recaptcha-response': token,
        type: this.custom,
      })
      this.showError = true
      if (!this.custom) {
        this.$emit('customError')
        return
      }
      if (!this.$v.$invalid) {
        this.$axios.$post('/api/request/send', option).then((res) => {
          this.success = true
          this.successText = res.data
          this.$refs.blockForm.reset()
          setTimeout(() => {
            this.success = false
            this.$emit('resetType')
          }, 4000)
        })
      }
    },
  },
}
</script>
<style lang="scss">
@import '~/components/dinamicForm/form';
.callback {
  max-width: 700px;
}
.success {
  .modalContent {
    padding: 81px 0px;
  }
  p {
    color: var(--black, #000);
    text-align: center;
    font-size: 24px;
    font-style: normal;
    font-weight: 400;
    line-height: 170%; /* 40.8px */
    margin: 0px;
  }
}
</style>
