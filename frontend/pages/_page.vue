<template>
  <div class="page">
    <sections-render :props-data="apiData"></sections-render>
  </div>
</template>

<script>
import { getPageBySlug } from '~/services/pageService'
import sectionsRender from '~/components/sectionsRender.vue'
export default {
  name: 'HomePage',
  components: { sectionsRender },
  async asyncData(ctx) {
    const slug = !ctx.params.page ? 'pro-kompaniyu' : ctx.params.page
    const data = await getPageBySlug(ctx, slug)
    return {
      apiData: data,
    }
  },
}
</script>

