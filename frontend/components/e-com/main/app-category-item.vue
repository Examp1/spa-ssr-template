<template>
    <div class="category_item">
        <nuxt-link :to="propsData.url">
            <img :src="path(propsData.img)" :alt="propsData.name" />
            <p>{{ propsData.name }}</p>
        </nuxt-link>
    </div>
</template>

<script>
export default {
    name: 'CategoryItem'
}
</script>

<style lang="scss" scoped>
.category_item {
    position: relative;

    a {
        display: block;
        border-radius: 5px;
        overflow: hidden;
        text-decoration: none;
        color: #000;
        @include transition;
        &:before{
            @include transition;
            content: '';
            background-color: #fff;
            opacity: 0;
            position: absolute;
            inset: 0px;
        }
        &:hover::before {
            opacity: .6;
        }
    }

    img {
        display: block;
        width: 100%;
        height: auto;
        max-height: 520px;
        object-fit: cover;
    }

    p {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        border-radius: 15px;
        background: rgba(255, 255, 255, 0.27);
        backdrop-filter: blur(7.5px);
        color: $text-primary;
        font-size: 32px;
        font-style: normal;
        font-weight: 600;
        line-height: 120%;
        padding: 40px;
        @include _768{
            padding: 30px;
            font-size: 26px;
        }
        @include _576{
            padding: 20px;
            font-size: 26px;
        }
    }

    @include _991 {
        padding: 0 5px;
        margin-bottom: 10px;

        p {
            font-size: 16px;
            line-height: 19px;
        }
    }

    @include _640 {
        p {
            min-height: 50px;
        }
    }

    @include _480 {
        p {
            min-height: 50px;
        }
    }
}</style>
