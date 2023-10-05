<template>
    <div class="seoText container">
        <div class="title">Інтернет-магазин меблів D2 Інтер'єр - купити меблі в Києві з доставкою по Україні</div>
        <div class="seoText_content">
            <p>Щиро вітаємо Вас в інтернет магазині меблів D2 Інтер'єр! Серед різних галузей діяльності ми обрали саме
                меблі. Чому? Тому що меблі для нас – це не просто предмети, якими можна обставити квартиру, офіс чи
                кав’ярню. Кожен стіл, стілець або крісло в нашому розумінні – це об'єкт щирого захоплення і нескінченного
                інтересу. Пориньте в різноманітний і захопливий світ меблів разом з нами!
                Сучасна мода на стилі інтер'єру – явище мінливе й непостійне. Ми уважно стежимо за трендами меблевої моди,
                тому клієнту, який Престижні меблі для керівника
                Коли мова заходить про престиж, ціна не має значення. Крісло і стіл для керівника компанії – це речі
                засадничі. У нас знайдуться як розкішні класичні моделі, так і лаконічний
            </p>
            <a href="" class="btn btn-more btn-more_black">Детальніше</a>
        </div>
    </div>
</template>

<script>
export default {

}
</script>

<style lang="scss" scoped>
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
            background: linear-gradient(180deg, rgba(252, 252, 252, 0.60) 0%, #FFF 100%);
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
}
</style>
