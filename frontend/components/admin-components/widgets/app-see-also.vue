<template>
    <div class="see-also">
        <div class="section-header">
            <div class="title">{{ propsData.title }}</div>
            <a href="" class="btn btn-all">Всі статті <i></i></a>
        </div>
        <div class="see-also_items">
            <div v-for="(li, idx) in propsData.list" :key="'li' + idx" class="see-also_item">
                <nuxt-link :to="li.link" class="see-also_item-container">
                    <img :src="li.image" alt="img">
                    <div class="see-also_item-content">
                        <strong>{{ li.title }}</strong>
                        <div v-html="li.text"></div>
                    </div>
                </nuxt-link>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'SeeAlso'
}
</script>

<style lang="scss" scoped>
.see-also {
    margin-bottom: 70px;

    .section-header {
        margin-bottom: 30px;
    }

    &_items {
        display: flex;
        margin: 0 -10px;

        .mainScreen_banner1-pagination {
            display: none;
        }

        @include _991 {
            margin: 0 -5px;
        }

        @include _480 {
            padding-bottom: 20px;
            position: relative;

            .mainScreen_banner1-pagination {
                display: block;
                bottom: 0;
            }
        }
    }

    &_item {
        width: 33.3%;
        padding: 0 10px;

        &-container {
            border: 1px solid #EEEEEE;
            border-radius: 5px;
            overflow: hidden;
            display: block;
            height: 100%;
            @include transition;

            &:hover {
                box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
            }

            img {
                display: block;
                width: 100%;
                height: auto;
            }
        }

        &-content {
            padding: 20px 10px 30px;

            strong {
                display: block;
                color: #000;
                margin-bottom: 10px;
                font-family: Calibri;
                font-size: 24px;
                font-weight: 700;
                line-height: 29px;
                overflow: hidden;
            }

            div::v-deep > * {
                display: block;
                font-family: Calibri;
                font-size: 16px;
                font-weight: 400;
                line-height: 24px;
                color: #696969;
            }

            @include _991 {
                padding: 15px 15px 20px;

                strong {
                    font-size: 20px;
                    line-height: 24px;
                }

                p {
                    font-size: 14px;
                    line-height: 21px;
                }
            }
        }

        @include _991 {
            padding: 0 5px;
        }

        @include _640 {
            width: 50%;

            &:nth-child(3) {
                display: none;
            }
        }

        @include _480 {
            width: 100%;

            &:nth-child(2) {
                display: none;
            }
        }
    }

    @include _480 {
        margin-bottom: 50px;
    }
}
</style>
