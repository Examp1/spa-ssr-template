<template>
    <div class="number-sections">
        <h2 v-if="propsData.title" v-html="propsData.title"></h2>
        <div class="numbers" :class="`gtc${propsData.items_in_row}`">
            <div
                v-for="(number, idx) in propsData.list"
                :key="'number' + idx"
                class="number"
            >
                <label>{{ number.number }}</label>
                <h3>{{ number.title }}</h3>
                <div class="redactor" v-html="number.text"></div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'AppNumber',
}
</script>

<style lang="scss" scoped>
h2 {
    margin-bottom: 80px;
}
.number {
    label {
        font-size: 59px;
        font-style: normal;
        font-weight: 400;
        line-height: 120%;
    }
    h3 {
        font-size: 21px;
        font-style: normal;
        font-weight: 400;
        line-height: 130%;
    }
    div::v-deep > * {
        font-size: 21px;
        font-style: normal;
        font-weight: 400;
        line-height: 130%;
    }
}
.numbers {
    display: grid;
}
</style>
