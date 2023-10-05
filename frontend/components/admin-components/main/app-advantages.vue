<template>
    <div class="advantage-sections">
        <h2 v-if="propsData.title" v-html="propsData.title"></h2>
        <div class="advantages" :class="`gtc${propsData.items_in_row}`">
            <div
                v-for="(advantage, idx) in propsData.list"
                :key="'advantage' + idx"
                class="advantage"
            >
                <img :src="path(advantage.image)" alt="" />
                <h3>{{ advantage.title }}</h3>
                <div class="redactor" v-html="advantage.text"></div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'AppAdvantages',
}
</script>

<style lang="scss" scoped>
h2 {
    margin-bottom: 80px;
}
.advantage {
    img {
        max-width: 140px;
        width: 100%;
        object-fit: contain;
        margin-bottom: 40px;
    }
    h3 {
        font-size: 27px;
        font-style: normal;
        font-weight: 600;
        line-height: 120%;
        margin-bottom: 15px;
    }
    div::v-deep > * {
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 125%;
    }
}
.advantages {
    display: grid;
}
</style>
