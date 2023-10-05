<template>
    <div :style="{ '--column': +propsData.title_column_select }">
        <h2 v-if="propsData.title" v-html="propsData.title"></h2>
        <div
            v-for="(row, idx) in propsData.rows"
            :key="'row' + idx"
            class="row"
        >
            <div
                v-for="(column, idx) in row"
                :key="'column' + idx"
                class="column redactor"
                v-html="column.column_text"
            ></div>
        </div>
        <app-btns v-if="propsData.btns" :props-data="propsData.btns"></app-btns>
    </div>
</template>

<script>
import AppBtns from '../../ui/app-btns.vue'

export default {
    name: 'TextNColumns',
    components: { AppBtns },
}
</script>

<style lang="scss" scoped>
.row {
    display: grid;
    grid-template-columns: repeat(var(--column), 1fr);
    grid-gap: 50px;
    @media (max-width: 768px) {
        grid-template-columns: repeat(2, 1fr);
    }
    @media (max-width: 576px) {
        grid-template-columns: repeat(1, 1fr);
    }
}
.column {
    font-weight: 400;
    font-size: 18px;
    line-height: 135%;
    letter-spacing: -0.01em;
    color: #1d1d1f;
    margin-bottom: 15px;
}
</style>
