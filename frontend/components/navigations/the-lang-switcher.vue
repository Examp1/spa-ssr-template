<template>
  <div class="lang-switcher">
    <span @click="changeLanguage('uk')">Укр</span>
    <span @click="changeLanguage('en')">Eng</span>
  </div>
</template>

<script>
export default {
  methods: {
    changeLanguage(lang) {
      this.$i18n.setLocale(lang)
    },
  },
}
</script>
<style lang="scss" scoped>
.lang-switcher {
  span {
    cursor: pointer;
    color: var(--text-primary, #353535);
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: 145%;
    letter-spacing: -0.32px;
  }
}
</style>
