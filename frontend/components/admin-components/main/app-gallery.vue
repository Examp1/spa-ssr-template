<template>
    <div class="gallery">
        <div class="section-header">
            <div class="title">{{ propsData.title }}</div>
            <div v-if="+propsData.show_btns" class="controls">
                <span class="control prev" @click="prev">p</span>
                <span class="control next" @click="next">n</span>
            </div>
        </div>
        <app-slider ref="slider" :props-data="slickOptions">
            <div v-for="(slide, idx) in propsData.list" :key="'slide' + idx" class="slide">
                <img :src="path(slide.image)" alt="" />
            </div>
        </app-slider>
    </div>
</template>

<script>
import appSlider from '../../ui/app-slider.vue'

export default {
    name: 'AppGallery',
    components: { appSlider },
    data() {
        return {
            slickOptions: {
                dots: true,
                infinite: true,
                arrows: false,
                swipe: true,
                swipeToSlide: true,
                slidesToShow: 1,
                centerPadding: '0px',
                responsive: [
                    {
                        breakpoint: 576,
                        settings: {
                            slidesToShow: 1,
                            arrows: false,
                            //   adaptiveHeight: true,
                        },
                    },
                ],
            },
        }
    },
    methods: {
        prev() {
            this.$refs.slider.prev()
        },
        next() {
            this.$refs.slider.next()
        },
    },
}
</script>

<style lang="scss">
.globalSlider {
    overflow-x: hidden;

    .slick-slide {
        min-height: 200px; // Вы можете изменить это значение на то, которое вам подходит

        img {
            max-width: 100%;
            height: auto;
        }
    }
}

.gallery {
    position: relative;
}

.controls {
    display: flex;
    grid-gap: 10px;
    .control {
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 100%;
        width: 30px;
        height: 30px;
        background-color: #FECA0A;
        transition: 0.3s;
        &:hover{
            background-color: #F4F4F4;
        }
    }
}
</style>
