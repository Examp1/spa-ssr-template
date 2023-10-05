<template>
    <div class="table-block">
        <app-table-item
            v-for="(row, idx) in propsData.rows"
            :key="'row' + idx"
            :props-data="row"
        ></app-table-item>
    </div>
</template>

<script>
import appTableItem from './app-table-item.vue'
export default {
    name: 'AppTable',
    components: { appTableItem },
}
</script>

<style lang="scss" scoped></style>
