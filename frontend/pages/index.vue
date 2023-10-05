<template>
    <div class="page">
        <sections-render :props-data="apiData" :home-page="true"></sections-render>
        <app-seo-text :props-data="apiData.translate.description"></app-seo-text>
    </div>
</template>

<script>
import { getPageBySlug } from '~/services/pageService'
import sectionsRender from '~/components/sections-render.vue'
import AppSeoText from '~/components/common/app-seo-text.vue'
export default {
    name: 'HomePage',
    components: { sectionsRender, AppSeoText },
    async asyncData(ctx) {
        const data = await getPageBySlug(ctx)
        return {
            apiData: data,
        }
    },
}
</script>
