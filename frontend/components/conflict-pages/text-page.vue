<template>
    <div class="page">
        <sections-render :props-data="propsData"></sections-render>
    </div>
</template>

<script>
import sectionsRender from '~/components/sections-render.vue'
export default {
    name: 'TextPage',
    components: { sectionsRender },
}
</script>
