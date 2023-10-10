<template>
    <div class="product container">
        <!-- <h2>{{ propsData.title }}</h2> -->
        <VueSlickCarousel ref="carousel" v-bind="slickOptions" :class="{ '--swiping': swiping === true }"
            @swipe="setCarouselSwiping(true)" @mouseup.native="setCarouselSwiping(false)"
            @touchend.native="setCarouselSwiping(false)">

            <app-product-card v-for="(product, idx) in activeSlideList" :key="'product' + idx"
                :props-data="product"></app-product-card>
        </VueSlickCarousel>
        <center>
            <app-link :to="catalog" class="btn stroke">Переглянути всі камери <i class="icon icon-big-arrow"></i></app-link>
        </center>
        <!-- </div> -->
    </div>
</template>

<script>
import VueSlickCarousel from 'vue-slick-carousel'
import appProductCard from '../main/app-product-card.vue'
//   import AppPopularProductsItem from './app-popularProductsItem.vue'
export default {
    name: 'ProductSlider',
    components: { appProductCard, VueSlickCarousel },
    props: {
        title: {
            type: String,
            default: '',
        },
    },
    data() {
        return {
            activeSlide: 1,
            swiping: false,
            slickOptions: {
                // dots: true,
                // focusOnSelect: true,
                infinite: true,
                speed: 500,
                swipe: true,
                swipeToSlide: true,
                slidesToShow: 3,
                centerPadding: '20px',
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 2,
                        },
                    },
                    {
                        breakpoint: 576,
                        settings: {
                            slidesToShow: 1,
                        },
                    },
                ],
            },
        }
    },
    computed: {
        activeSlideList() {
            switch (this.activeSlide) {
                case 2:
                    return this.propsData.tab_2_list
                case 3:
                    return this.propsData.tab_3_list
                default:
                    return this.propsData.tab_1_list
            }
        },
    },
    methods: {
        setCarouselSwiping(state) {
            this.swiping = state
        },
        setActiveTab(idx) {
            this.activeSlide = idx
        },
    },
}
</script>

<style lang="scss" scoped>
.product::v-deep {
    .slick-arrow::before {
        font-family: 'd2';
        content: "\e907";
        width: 46px;
        height: 46px;
        color: #000;
    }

    .slick-prev {
        transform: rotate(180deg) translateY(10px);
    }
}

.btn {
    margin: 80px auto 0px auto;
}

.product {
    &_tabs {
        margin-bottom: 24px;

        ul {
            display: flex;

            @include _991 {
                margin-bottom: 30px;
            }
        }

        li {
            margin-right: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            height: 40px;
            cursor: pointer;
            border-radius: 5px;
            padding: 0 15px;
            background-color: #eeeeee;
            color: #000;
            font-family: Calibri;
            font-size: 16px;
            font-weight: 400;
            @include transition;
            text-decoration: none;

            &:hover {
                background-color: #feca0a;
            }

            @include _991 {
                height: 36px;
            }

            @include _991 {
                margin-right: 10px;
            }
        }

        li.active {
            background-color: #feca0a;
        }
    }

    &_items {
        display: grid;
        // grid-template-columns: repeat(4, 1fr);
        position: relative;
        margin: 0 -10px;

        .mainScreen_banner1-pagination {
            display: none;
        }

        @include _991 {
            padding-bottom: 20px;
            margin: 0 -5px;

            .mainScreen_banner1-pagination {
                display: block;
                bottom: 0;

                li {
                    margin: 0 2.5px;
                }
            }
        }

    }

    &_like {
        position: absolute;
        top: 15px;
        right: 15px;
        @include size(24px);

        &:after {
            @include after;
            position: absolute;
            @include transition;
            @include size(100%, 100%);
            opacity: 1;
            //   background: url(../img/icon-like-gray.svg) no-repeat;
        }

        &:before {
            @include after;
            @include transition;
            position: absolute;
            @include size(100%, 100%);
            opacity: 0;
            //   background: url(../img/icon-like-red.svg) no-repeat;
        }

        @include _991 {
            @include size(18px);

            &:after {
                background-size: contain;
            }

            &:before {
                background-size: contain;
            }
        }
    }

    @include _991 {
        margin-bottom: 70px;
    }

    @include _480 {
        margin-bottom: 50px;
    }
}
</style>
