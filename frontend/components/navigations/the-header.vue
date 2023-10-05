<template>
  <header class="header">
    <header-top-side v-if="getSettings.promobar_text"></header-top-side>
    <header-body></header-body>
    <header-nav></header-nav>
  </header>
</template>

<script>
import { mapGetters } from 'vuex'
import HeaderTopSide from './hedaer-components/header-top-side.vue'
import HeaderBody from './hedaer-components/header-body.vue'
import HeaderNav from './hedaer-components/header-nav.vue'
// import theLangSwitcher from './the-lang-switcher.vue'
export default {
  name: 'TheHeader',
  components: { HeaderTopSide, HeaderBody, HeaderNav },
  computed: {
    ...mapGetters({
      getSettings: 'app_settings/getSettings',
    }),
  },
}
</script>

<style lang="scss" scoped>
.header {
  margin-bottom: 30px;
}
</style>
