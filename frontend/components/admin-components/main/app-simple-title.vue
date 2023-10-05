<template>
    <div class="simple-title">
        <span class="subtitle">{{ propsData.subtitle }}</span>
        <div class="wrp">
            <h2 v-if="propsData.title" v-html="propsData.title"></h2>
            <app-btns
                v-if="propsData.btns"
                :props-data="propsData.btns"
            ></app-btns>
        </div>
    </div>
</template>

<script>
import AppBtns from '../../ui/app-btns.vue'
export default {
    name: 'SimpleTitle',
    components: {
        AppBtns,
    },
}
</script>

<style lang="scss" scoped>
.wrp {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
</style>
