import { VuelidatePlugin } from '@vuelidate/core'

export default ({ app }, inject) => {
  app.use(VuelidatePlugin)
}
