<template>
    <div class="page">
        <sections-render :props-data="propsData"></sections-render>
        <app-seo-text :props-data="propsData.translate.description"></app-seo-text>
        <appCtaStaticVue></appCtaStaticVue>
    </div>
</template>

<script>
import sectionsRender from '~/components/sections-render.vue'
import AppSeoText from '~/components/common/app-seo-text.vue'
import appCtaStaticVue from '~/components/admin-components/main/app-cta-static.vue'
export default {
    name: 'TextPage',
    components: { sectionsRender, AppSeoText, appCtaStaticVue },
}
</script>
