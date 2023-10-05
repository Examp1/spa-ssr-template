<template>
    <div class="text-divider redactor" v-html="propsData.text"></div>
</template>

<script>
export default {
    name: 'TextDivider',
}
</script>

<style lang="scss" scoped>
.text-divider::v-deep * {
    font-style: italic;
    font-size: 22px;
    font-style: italic;
    font-weight: 400;
    line-height: 150%; /* 33px */
}
</style>
