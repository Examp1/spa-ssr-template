import Vue from 'vue'
import { ValidationProvider, ValidationObserver, extend  } from 'vee-validate';
import * as rules from 'vee-validate/dist/rules';
import appLink from '~/components/common/app-link.vue'
// глобальные подключение
Vue.component('ValidationProvider', ValidationProvider);
Vue.component('ValidationObserver', ValidationObserver);
// правила валидации
Object.keys(rules).forEach(rule => {
  // eslint-disable-next-line import/namespace
  extend(rule, rules[rule]);
});
// глобальные компоненты
Vue.component('appLink', appLink);
