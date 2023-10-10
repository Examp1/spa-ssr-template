<template>
    <div class="subscription">
        <div class="container">
            <div class="subscription_container">
                <div class="subscription_text">
                    <h3>Розсилка з новинками</h3>
                    <div class="desc">і спеціальними пропозиціями EZVIZ</div>
                </div>
                <div class="subscription_form">
                    <form action="#">
                        <input type="text" placeholder="Ваша email адреса">
                        <input class="btn fill" type="submit" value="Підписатися">
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
    background: $bg-gradient-primary;
    padding: 49px 0;

    h3 {
        color: $text-primary;
        margin-bottom: 10px;
        text-align: center;
        font-family: Calibri;
        font-size: 32px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
    }

    .desc {
        color: $text-primary;
        text-align: center;
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 120%;
        /* 19.2px */
    }

    &_container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-direction: column;

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
        width: 100%;
        margin-top: 30px;

        form {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;

            input[type="text"] {
                display: block;
                border-radius: 5px;
                background-color: #F6F6F6;
                border: 0;
                padding: 0 15px;
                @include size(50%, 50px);
                font-family: Calibri;
                border-radius: 50px;
                max-width: 254px;
                width: 100%;
                font-size: 16px;
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
                max-width: 229px;
                width: 100%;
                color: #F6F6F6;

                &:hover {
                    color: #000;
                    background-color: #FFF101;
                }
            }

            @include _480 {
                display: block;

                input[type="text"] {
                    margin: 0 0 20px 0;
                    max-width: unset;
                }

                input[type="submit"] {
                    width: 100%;
                    max-width: unset;
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
