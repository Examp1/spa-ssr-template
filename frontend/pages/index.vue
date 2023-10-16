<template>
    <div class="page">
        <app-banners-sections></app-banners-sections>
        <sections-render :props-data="apiData" :home-page="true"></sections-render>
        <app-seo-text :props-data="apiData.translate.description"></app-seo-text>
    </div>
</template>

<script>
import { getPageBySlug } from '~/services/pageService'
import sectionsRender from '~/components/sections-render.vue'
import AppSeoText from '~/components/common/app-seo-text.vue'
import AppBannersSections from '~/components/e-com/main/app-banners-sections.vue'
export default {
    name: 'HomePage',
    components: { sectionsRender, AppSeoText, AppBannersSections },
    async asyncData(ctx) {
        const data = await getPageBySlug(ctx)
        return {
            apiData: data,
        }
    },
}
</script>
