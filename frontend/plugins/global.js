import Vue from 'vue'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import appLink from '~/components/common/app-link.vue'
// глобальные подключение
Vue.component('ValidationProvider', ValidationProvider)
Vue.component('ValidationObserver', ValidationObserver)

// глобальные компоненты
Vue.component('AppLink', appLink)
