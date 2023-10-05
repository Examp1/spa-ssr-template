<template>
    <!-- :style="containerBg" -->
    <!-- <div class="cta">
        <div class="container">
            <h2 v-if="propsData.title" v-html="propsData.title"></h2>
            <div class="redactor" v-html="propsData.text"></div>
            <app-btns v-if="propsData.btns" :props-data="propsData.btns"></app-btns>
        </div>
    </div> -->
    <div class="subscription">
        <div class="container">
            <div class="subscription_container">
                <div class="subscription_text">
                    <div v-if="propsData.title" class="title" v-html="propsData.title"></div>
                    <div class="redactor" v-html="propsData.text"></div>
                </div>
                <div class="subscription_form">
                    <form action="#">
                        <input type="text" placeholder="Ваша email адреса">
                        <input class="btn btn-more btn-more_black" type="submit" value="Підписатися">
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
// import appBtns from '../../ui/app-btns.vue'
export default {
    name: 'AppCta',
    // components: { appBtns },
    computed: {
        containerBg() {
            return `background-color: ${this.propsData.bg_color}`
        },
    },
}
</script>

<style lang="scss" scoped>
.cta {
    .container {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 50px 0px;
        border-radius: 22px;
    }
}

.subscription {
    background-color: #FECA0A;
    padding: 49px 0;

    &_container {
        display: flex;
        justify-content: space-between;
        align-items: center;

        @include _640 {
            display: block;
        }
    }

    &_text {
        margin-right: 20px;
        max-width: 450px;

        .title {
            margin-bottom: 10px;
        }

        p {
            display: block;
            font-family: Calibri;
            font-size: 16px;
            font-weight: 400;
            line-height: 24px;
            color: #7F6505;
        }

        @include _991 {
            p {
                font-size: 14px;
                line-height: 21px;
            }
        }
    }

    &_form {
        width: calc(50% - 10px);
        min-width: calc(50% - 10px);
        ;

        form {
            display: flex;
            align-items: center;
            max-width: 100%;

            input[type="text"] {
                display: block;
                border-radius: 5px;
                background-color: #F6F6F6;
                border: 0;
                padding: 0 15px;
                @include size(50%, 50px);
                font-family: Calibri;
                font-size: 14px;
                font-weight: 400;
                color: #9E9E9E;
                @include transition;
                margin-right: 20px;

                &:hover {
                    background-color: #fff;
                }
            }

            input[type="submit"] {
                display: block;
                height: 50px;
                border: none;
                cursor: pointer;
                padding: 0 30px;
                font-family: Calibri;
                font-size: 16px;
                font-weight: 700;
                line-height: 20px;
                color: #F6F6F6;

                &:hover {
                    color: #000;
                    background-color: #FFF101;
                }
            }

            @include _991 {
                justify-content: flex-end;

                input[type="text"] {
                    margin-right: 10px;
                }

                input[type="submit"] {
                    padding: 0 16px;
                }
            }

            @include _480 {
                display: block;

                input[type="text"] {
                    margin: 0 0 20px 0;
                }

                input[type="submit"] {
                    width: 100%;
                }
            }
        }
    }

    @include _991 {
        .title {
            line-height: 31px;
        }
    }

    @include _640 {
        &_text {
            width: 100%;
            margin: 0 0 30px 0;
        }

        &_form {
            width: 100%;
            min-width: 1px;

            form {
                justify-content: flex-start;

                input[type="text"] {
                    width: 100%;
                }

                input[type="submit"] {
                    padding: 0 45px;
                }
            }
        }
    }
}</style>
