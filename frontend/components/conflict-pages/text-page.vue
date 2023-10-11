<template>
    <div class="page">
        <sections-render :props-data="propsData.constructor">
            <template #simpleFirstScreen>
                <first-screen
                    v-if="!hasFirstScreen"
                    :props-data="simpleFirstScreen"
                ></first-screen>
            </template>
        </sections-render>
        <app-seo-text :props-data="propsData.translate.description"></app-seo-text>
        <appCtaStaticVue></appCtaStaticVue>
    </div>
</template>

<script>
import sectionsRender from '~/components/sections-render.vue'
import AppSeoText from '~/components/common/app-seo-text.vue'
import appCtaStaticVue from '~/components/admin-components/main/app-cta-static.vue'
import appFirstScreen from '~/components/admin-components/main/app-firstScreen.vue'
export default {
    name: 'TextPage',
    components: { sectionsRender, AppSeoText, appCtaStaticVue, 'first-screen': appFirstScreen },
    computed: {
        simpleFirstScreen() {
            return {
                title: this.propsData.translate.title,
                text: this.propsData.translate.description,
                ...this.propsData.translate.main_screen,
                image: this.propsData.translate.image,
                image_mob: this.propsData.translate.image_mob,
            }
        },
        hasFirstScreen() {
            return this.propsData.constructor.some(
                (item) => item.component === 'first-screen' && +item.visibility
            )
        },
    },
}
</script>
