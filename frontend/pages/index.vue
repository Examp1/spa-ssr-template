<template>
    <div class="page">
        <sections-render :props-data="apiData" :home-page="true"></sections-render>
        <app-seo-text :props-data="apiData.translate.description"></app-seo-text>
        <appCtaStaticVue></appCtaStaticVue>
    </div>
</template>

<script>
import { getPageBySlug } from '~/services/pageService'
import sectionsRender from '~/components/sections-render.vue'
import AppSeoText from '~/components/common/app-seo-text.vue'
import appCtaStaticVue from '~/components/admin-components/main/app-cta-static.vue'
export default {
    name: 'HomePage',
    components: { sectionsRender, AppSeoText, appCtaStaticVue },
    async asyncData(ctx) {
        const data = await getPageBySlug(ctx)
        return {
            apiData: data,
        }
    },
}
</script>
