<template>
    <div class="page">
        <sections-render
            :props-data="apiData.constructor"
            :home-page="true"
        >
            <template #simpleFirstScreen>
                <first-screen
                    v-if="!hasFirstScreen"
                    :props-data="simpleFirstScreen"
                ></first-screen>
            </template>
        </sections-render>
        <app-seo-text :props-data="apiData.translate.description"></app-seo-text>
        <appCtaStaticVue></appCtaStaticVue>
    </div>
</template>

<script>
import { getPageBySlug } from '~/services/pageService'
import sectionsRender from '~/components/sections-render.vue'
import AppSeoText from '~/components/common/app-seo-text.vue'
import appCtaStaticVue from '~/components/admin-components/main/app-cta-static.vue'
import appFirstScreen from '~/components/admin-components/main/app-firstScreen.vue'
export default {
    name: 'HomePage',
    components: { sectionsRender, AppSeoText, appCtaStaticVue, 'first-screen': appFirstScreen },
    async asyncData(ctx) {
        const data = await getPageBySlug(ctx)
        return {
            apiData: data,
        }
    },
    computed: {
        simpleFirstScreen() {
            return {
                title: this.apiData.translate.title,
                text: this.apiData.translate.description,
                ...this.apiData.translate.main_screen,
                image: this.apiData.translate.image,
                image_mob: this.apiData.translate.image_mob,
            }
        },
        hasFirstScreen() {
            return this.apiData.constructor.some(
                (item) => item.component === 'first-screen' && +item.visibility
            )
        },
    },
}
</script>
