<template>
    <category-page
        v-if="apiData.type === 'category'"
        :props-data="apiData"
    ></category-page>
    <text-page v-else :props-data="apiData"></text-page>
</template>

<script>
import categoryPage from '../../components/conflict-pages/category-page.vue'
import TextPage from '../../components/conflict-pages/text-page.vue'
import { getPageBySlug } from '~/services/pageService'
export default {
    name: 'AdapterPage',
    components: { categoryPage, TextPage },
    async asyncData(ctx) {
        const data = await getPageBySlug(ctx)
        return {
            apiData: data,
        }
    },
}
</script>
