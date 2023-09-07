<template>
  <header>
    <div class="container">
      <component
        :is="$route.path === '/' ? 'span' : 'app-link'"
        :to="'/'"
        class="logo"
      >
        <img :src="path(getLogo)" alt="" />
      </component>
      <nav class="menu">
        <app-link
          v-for="(li, idx) in getHeaderMenu"
          :key="'li' + idx"
          :to="li.url"
        >
          {{ li.name }}
        </app-link>
      </nav>
    </div>
  </header>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
  name: 'TheHeader',
  computed: {
    ...mapGetters({
      getLogo: 'app_settings/getLogo',
      getHeaderMenu: 'menu/getHeaderMenu',
    }),
  },
}
</script>

<style lang="scss" scoped>
.logo{
  height: 40px;
  img{
    height: 100%;
  }
}
header {
  background-color: #cbcbcb;
  position: sticky;
  top: 0;
  z-index: 999;
  .container {
    height: 75px;
    display: flex;
    align-items: center;
  }
  .menu {
    display: flex;
    list-style: none;
    grid-gap: 25px;
    @include md {
      display: none;
    }
    a {
      color: #000;
      text-decoration: none;
      font-size: 17px;
      font-style: normal;
      font-weight: 400;
      line-height: 145%;
      letter-spacing: -0.34px;
    }
  }
}
</style>
