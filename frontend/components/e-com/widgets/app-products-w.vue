<template>
    <div class="category-w">
        <VueSlickCarousel
            v-if="propsData.type === 'slider'"
            ref="carousel"
            class="type-slider"
            v-bind="slickOptions"
            :class="{ '--swiping': swiping === true }"
            @swipe="setCarouselSwiping(true)"
            @mouseup.native="setCarouselSwiping(false)"
            @touchend.native="setCarouselSwiping(false)"
        >

            <app-product-card
                v-for="(product, idx) in propsData.list"
                :key="'product' + idx"
                :props-data="product"
            ></app-product-card>
        </VueSlickCarousel>
        <div class="type-list">
            <app-product-card
                v-for="(product, idx) in propsData.list"
                :key="'product-list' + idx"
                :props-data="product"
            ></app-product-card>
        </div>
    </div>
</template>

<script>
import VueSlickCarousel from 'vue-slick-carousel'
import appProductCard from '../main/app-product-card.vue'
export default {
    name: 'ProductW',
    components: { appProductCard, VueSlickCarousel },
    data() {
        return {
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
.category-w::v-deep {
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

.type-list {
    display: grid;
    grid-gap: 40px;
    grid-template-columns: repeat(3, 1fr);

    @include _1024 {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>
