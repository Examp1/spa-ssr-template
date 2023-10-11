<template>
    <div class="seoText container">
        <!-- <div class="title">Інтернет-магазин меблів D2 Інтер'єр - купити меблі в Києві з доставкою по Україні</div> -->
        <div
            class="seoText_content"
            :class="{ 'open': isOpen }"
        >
            <div
                class="redactor"
                v-html="propsData"
            >
            </div>
            <span
                class="btn fill btn-more"
                @click="isOpen = true"
            >Детальніше</span>
        </div>
    </div>
</template>

<script>
export default {
    name: 'SeoText',
    data() {
        return {
            isOpen: false
        }
    },
}
</script>

<style lang="scss" scoped>
.redactor {
    max-height: 120px;
    overflow: hidden;
    @include transition;
}

.seoText {
    margin-bottom: 105px;

    @include _991 {
        margin-bottom: 70px;
    }

    .title {
        margin-bottom: 20px;
    }

    &_content {
        position: relative;

        &::before {
            content: '';
            position: absolute;
            height: 94px;
            width: 100%;
            bottom: 0;
            @include transition;
            background: linear-gradient(180deg, rgba(252, 252, 252, 0.60) 0%, #FFF 100%);
        }

        &.open {
            &::before{
                opacity: 0;
            }
            .redactor{
                max-height: 10000px;
            }
            .btn{
                opacity: 0;
            }
        }
        p {
            display: block;
            font-size: 16px;
            font-weight: 400;
            line-height: 25px;
            color: #595959;
        }

        &:after {
            @include after;
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            height: 60px;
            width: 100%;
            // background: url(../img/shadow.svg) no-repeat 0 100%;
            background-size: 100% auto;
        }

        .btn-more {
            position: absolute;
            @include transition;
            bottom: 0;
            left: 50%;
            transform: translate(-50%, 0);
            z-index: 2;
            font-size: 16px;
        }

        @include _991 {
            p {
                font-size: 14px;
                line-height: 20px;
            }

            .btn-more {
                bottom: 20px;
            }
        }
    }

    @include _480 {
        margin-bottom: 50px;
    }
}</style>
