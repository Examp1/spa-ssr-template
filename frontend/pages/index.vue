<template>
  <div class="container">
    <nuxt-link :to="'/about'">about</nuxt-link>
    {{ getMode }}
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { getPageBySlug } from '~/services/pageService'
export default {
  name: 'HomePage',
  async asyncData(ctx) {
    const data = await getPageBySlug(ctx, '/')
    return {
      apiData: data,
    }
  },
  computed: {
    ...mapGetters({
      getMode: 'checkMode/getMode',
    }),
  },
}
</script>
<style lang="scss" scoped>
.app {
  font-size: 100px;
  width: 100%;
  height: 100vh;
}
</style>
