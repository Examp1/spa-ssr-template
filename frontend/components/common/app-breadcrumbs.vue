<template>
    <ul class="breadcrumbs">
        <li class="breadcrumbsItem"><nuxt-link to="/">Головна</nuxt-link></li>
        <li
            v-for="(link, idx) in propsData"
            :key="idx"
            class="breadcrumbsItem"
        >
            <component
                :is="propsData.length - 1 !== idx ? 'app-link' : 'span'"
                :to="link.link"
            >{{ link.name }}</component>
        </li>
    </ul>
</template>

<script>
export default {}
</script>

<style lang="scss" scoped>
.breadcrumbs {
    display: flex;
    margin-top: 30px;
    margin-bottom: 30px;

    li {
        position: relative;
        &:not(:last-of-type)::after {
            content: "\e907";
            margin: 0px 5px;
            font-family: d2;
            font-size: 12px;
            color: #000
        }

        a,
        span {
            color: var(--, $gray);
            font-size: 16px;
            font-style: normal;
            font-weight: 400;
            line-height: normal;
            text-decoration: none;
        }
    }

    li:last-of-type>span {
        color: var(--Black, #000);
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
    }
}
</style>
