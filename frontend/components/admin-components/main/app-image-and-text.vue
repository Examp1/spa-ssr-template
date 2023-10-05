<template>
    <div class="image-and-text">
        <h2 v-if="propsData.title" v-html="propsData.title"></h2>
        <div
            class="wrp"
            :class="propsData.image_position"
            :style="{
                '--imgWidth': calculateTextAndImageWidth.image,
                '--textWidth': calculateTextAndImageWidth.text,
            }"
        >
            <div class="textWrapper">
                <div class="redactor" v-html="propsData.description"></div>
                <div>
                    <app-btns
                        v-if="propsData.btns"
                        :props-data="propsData.btns"
                    ></app-btns>
                </div>
            </div>
            <picture class="imageWrapper">
                <source
                    media="(max-width: 768px)"
                    :srcset="path(propsData.image_mob)"
                />
                <img :src="path(propsData.image)" alt="" />
            </picture>
        </div>
    </div>
</template>

<script>
import AppBtns from '../../ui/app-btns.vue'

export default {
    name: 'ImageAndText',
    components: { AppBtns },
    computed: {
        calculateTextAndImageWidth() {
            return {
                text: (10 - this.propsData.column_width) * 10 + '%',
                image: this.propsData.column_width * 10 + '%',
            }
        },
    },
}
</script>

<style lang="scss" scoped>
.image-and-text {
    .wrp {
        display: flex;
        grid-gap: 60px;
        @include _640 {
            flex-direction: column;
        }
        .textWrapper {
            width: var(--textWidth);
            .redactor {
                position: sticky;
                top: 0;
            }
            @include _640 {
                width: 100%;
            }
        }
        .imageWrapper {
            width: var(--imgWidth);
            img {
                width: 100%;
                position: sticky;
                top: 0;
            }
            @include _640 {
                width: 100%;
            }
        }
        &.left {
            flex-direction: row-reverse;
        }
    }
}
</style>
