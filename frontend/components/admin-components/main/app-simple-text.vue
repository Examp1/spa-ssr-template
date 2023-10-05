<template>
    <div class="simple-text" :class="propsData.text_align">
        <div
            class="textWrapper"
            :style="{
                '--width': propsData.text_width + '%',
            }"
        >
            <h2 v-if="propsData.title" v-html="propsData.title"></h2>
            <div class="redactor" v-html="propsData.description"></div>
            <app-btns
                v-if="propsData.btns"
                :props-data="propsData.btns"
            ></app-btns>
        </div>
    </div>
</template>

<script>
import appBtns from '../../ui/app-btns.vue'
export default {
    name: 'SimpleText',
    components: { appBtns },
}
</script>

<style lang="scss" scoped>
.simple-text {
    display: flex;
    &.center {
        justify-content: center;
    }
    &.left {
        justify-content: flex-start;
    }
    &.right {
        justify-content: flex-end;
    }
}
.textWrapper {
    width: var(--width);
}
</style>
