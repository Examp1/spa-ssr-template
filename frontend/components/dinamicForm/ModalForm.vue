<template>
  <div class="modal dinamicForm" @mousedown.self="close">
    <form v-if="!success" class="modalContent">
      <span class="close" @click.stop="close">&times;</span>
      <component
        :is="item.type"
        v-for="(item, idx) in getFormData.form_data"
        :key="idx"
        v-model="InputValues[item.name]"
        :propsData="item"
        :type="'modal'"
        :inputData="$v.InputValues[item.name]"
        :messages="item.messages"
        :showError="showError"
        @changeChekbox="changeChekbox"
      ></component>
      <span class="btn" @click="sendForm"
        >{{ getFormData.form_btn_name }}<span class="icon icon-send"></span
      ></span>
    </form>
    <div v-else class="success modalContent">
      <span class="close" @click.stop="close">&times;</span>
      <!-- <img
        src="../../assets/img/categori/succesfull.svg?_v=1666255489213"
        alt="Забраження успішного відправлення"
      /> -->
      <h2>{{ successText?.success_title }}</h2>
      <p class="successfull__text">
        {{ successText?.success_text }}
      </p>
      <!-- <div class="btnWrap">
        <nuxt-link class="btn" :to="'/'" @click.native="goHome"
          >{{ $t('success_button') }}
          <span class="icon icon-arrow-right-2"></span
        ></nuxt-link>
      </div> -->
    </div>
  </div>
</template>

<script>
import { required, email, minLength, maxLength } from '@vuelidate/validators'
import { mapGetters, mapActions } from 'vuex'
import formText from '../dinamicForm/formText.vue'
import formInput from '../dinamicForm/formInput.vue'
import formTitle from '../dinamicForm/formTitle.vue'
import FormEditor from '../dinamicForm/formEditor.vue'
import FormCheckbox from '../dinamicForm/formCheckbox.vue'
import FormDate from './formDate.vue'
export default {
  name: 'FormModal',
  components: {
    formInput,
    formText,
    formTitle,
    FormEditor,
    FormCheckbox,
    FormDate,
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
      getFormData: 'modals/getFormData',
      getProduct: 'modals/getProduct',
    }),
  },
  validations() {
    const temp = {}
    temp.InputValues = {}
    this.getFormData.form_data.forEach((el) => {
      temp.InputValues[el.name] = {}
      // eslint-disable-next-line no-unused-expressions
      !(
        //   }
        this.getFormData.form_data.forEach((el) => {
          if (el.rules && el.name !== 'hidden') {
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
      )
    })

    return temp
  },
  mounted() {
    this.lockScroll()

    if (this.getFormData) {
      this.getFormData.form_data.forEach((el) => {
        this.$set(this.InputValues, el.name, '')
      })
    }
  },
  methods: {
    ...mapActions({
      setOpenModal: 'modals/setOpenModal',
    }),
    close() {
      this.setOpenModal(null)
    },
    changeChekbox(name, data) {
      this[name] = data
    },
    async sendForm() {
      const token = await this.$recaptcha.execute('login')
      const option = Object.assign(this.InputValues, {
        form_id: this.getFormData.form_id,
        'g-recaptcha-response': token,
      })
      if (this.getProduct) {
        option.product_text = this.getProduct
      }
      this.showError = true
      if (!this.$v.$invalid) {
        this.$axios.$post('/api/request/send', option).then((res) => {
          this.success = true
          this.successText = res.data
          setTimeout(() => {
            this.close()
          }, 4000)
        })
      }
    },
  },
  beforeDestroy() {
    this.unLockScroll()
  },
}
</script>

<style lang="scss" scoped>
@import '~/components/dinamicForm/form';
</style>
