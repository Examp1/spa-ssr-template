<template>
    <div class="furnitureByRoom">
        <h2 v-if="propsData.title" class="title" v-html="propsData.title"></h2>
        <div class="furnitureByRoom_items" :class="`gtc${propsData.title_column_select}`">
            <div v-for="(block, idx) in propsData.list" :key="'block' + idx" class="furnitureByRoom_item">
                <nuxt-link :to="block.link">
                    <!-- <h3 class="block-title">{{ block.title }}</h3> -->
                    <img :src="path(block.image)" :alt="'block' + idx" />
                    <div class="redactor" v-html="block.text"></div>
                    <p>{{ block.title }}</p>
                </nuxt-link>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'AppBlocks',
}
</script>

<style lang="scss" scoped>
.furnitureByRoom {
    margin-bottom: 50px;

    .title {
        margin-bottom: 30px;
    }

    &_items {
        display: grid;
        flex-wrap: wrap;
        margin: 0 -10px;

        @include _991 {
            margin: 0 -5px;
        }
    }

    &_item {
        margin: 0 0 20px;
        padding: 0 10px;

        a {
            display: block;
            border-radius: 5px;
            border: 1px solid #EEEEEE;
            overflow: hidden;
            text-decoration: none;
            color: #000;
            @include transition;

            &:hover {
                box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
            }
        }

        img {
            display: block;
            width: 100%;
            height: auto;
            max-height: 315px;
            object-fit: cover;
        }

        p {
            display: flex;
            align-items: center;
            text-align: center;
            justify-content: center;
            min-height: 84px;
            padding: 0 15px;
            color: #000;
            font-family: Calibri;
            font-size: 20px;
            font-weight: 400;
            line-height: 24px;
        }

        @include _991 {
            padding: 0 5px;
            margin-bottom: 10px;

            p {
                min-height: 64px;
                font-size: 16px;
                line-height: 19px;
            }
        }
    }
}
</style>
