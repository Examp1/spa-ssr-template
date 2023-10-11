<template>
    <div class="product_item">
        <nuxt-link
            :to="propsData.path"
            class="product_img"
        >
            <img
                :src="previewPhoto"
                :alt="propsData.alt"
            />
        </nuxt-link>
        <div class="product_content">
            <div class="product_content-block">
                <!-- <div class="product_item-inStock"><i></i> В наявності</div> -->
                <h3 class="product_item-title">{{ propsData.name }}</h3>
                <div class="article">Код товару: 99-00001781</div>
                <div class="desc">Вулична Wi-Fi камера з мікрофоном</div>
            </div>
            <div class="product_prices">
                <p
                    v-if="+propsData.old_price"
                    class="old"
                >{{ propsData.old_price }}₴</p>
                <p class="new">{{ propsData.price }}₴</p>
            </div>
            <div
                v-if="propsData.prices.length"
                class="options"
            >
                <p>Комплектація</p>
                <details>
                    <p
                        v-for="(opt, idx) in propsData.prices"
                        :key="'opt' + idx"
                        :value="opt.id"
                        @click="goToProduct(propsData?.prices?.length, opt.id)"
                    >{{ opt.name }}</p>
                </details>
            </div>
            <nuxt-link
                :to="propsData.path"
                class="btn fill"
            >Купити <i class="icon icon-big-arrow"></i></nuxt-link>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ProductCard',
    data() {
        return {
            previewPhoto: this.path(this.propsData.image),
        }
    },
    methods: {
        goToProduct(lng, id) {
            console.log(123);
            const routeObj = {
                path: `/${this.propsData?.path}`,
            }
            if (lng > 1) {
                routeObj.query = { optionId: id }
            }
            this.$router.push(routeObj)
        },
    },
}
</script>

<style lang="scss" scoped>
h3 {
    color: $text-primary;
    font-family: SF Pro Display;
    font-size: 24px;
    font-style: normal;
    font-weight: 600;
    line-height: 120%;
}

// .product {

.product_content {
    text-align: center;
}

.product_img img {
    object-fit: contain;
    width: 100%;
    height: 300px;
}



.article {
    color: $text-secondary;
    font-family: SF Pro Display;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: 120%;
    margin-bottom: 20px;
}

.desc {
    color: $black-gray;
    font-family: SF Pro Display;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: 120%;
    margin-bottom: 20px;
}

.product_prices {
    .old {
        color: $text-secondary;
        font-family: SF Pro Display;
        font-size: 20px;
        font-style: normal;
        font-weight: 400;
        line-height: 140%;
        text-decoration-line: strikethrough;
    }

    .new {
        color: $text-primary;
        font-family: SF Pro Display;
        font-size: 24px;
        font-style: normal;
        font-weight: 400;
        line-height: 140%;
    }
}

.btn {
    margin-top: 20px;
}

.options {
    margin-top: 20px;
}</style>
