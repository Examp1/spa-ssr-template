<template>
  <div class="page">
    <sections-render :props-data="apiData.constructor"></sections-render>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { getPageBySlug } from '~/services/pageService'
import sectionsRender from '~/components/sectionsRender.vue'
export default {
  name: 'HomePage',
  components: { sectionsRender },
  async asyncData(ctx) {
    const data = await getPageBySlug(ctx, 'test')
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
  width: 100%;
  height: 100vh;
}
</style>
