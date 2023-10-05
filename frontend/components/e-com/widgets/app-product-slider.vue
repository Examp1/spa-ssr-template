<template>
  <!-- <section
      class="productList"
      :style="{
        '--dotCount': Math.ceil(
          propsData.tab_1_list.length / slickOptions.slidesToShow
        ),
      }"
    >
      <div class="popular__title">
        <h2 class="title">{{ propsData.title || title }}</h2>
        <ul class="tab">
          <li :class="{ active: activeSlide === 1 }" @click="setActiveTab(1)">
            {{ propsData.tab_1_title }}
          </li>
          <li :class="{ active: activeSlide === 2 }" @click="setActiveTab(2)">
            {{ propsData.tab_2_title }}
          </li>
          <li :class="{ active: activeSlide === 3 }" @click="setActiveTab(3)">
            {{ propsData.tab_3_title }}
          </li>
        </ul>
      </div>
      <div class="product">
        <VueSlickCarousel
          ref="carousel"
          v-bind="slickOptions"
          :class="{ '--swiping': swiping === true }"
          @swipe="setCarouselSwiping(true)"
          @mouseup.native="setCarouselSwiping(false)"
          @touchend.native="setCarouselSwiping(false)"
        >
          <div
            v-for="(product, idx) in activeSlideList"
            :key="'product' + idx"
            class="slide"
          >
            <app-popular-products-item
              :props-data="product"
              class="slide-link"
            ></app-popular-products-item>
          </div>
          <template #customPaging="page">
            {{ page }}
          </template>
        </VueSlickCarousel>
      </div>
    </section> -->
  <div class="product container">
    <div class="section-header">
      <div class="title">Акції</div>
      <a href="" class="view-all">Всі акції <i></i></a>
    </div>
    <div class="product_tabs">
      <ul>
        <li :class="{ active: activeSlide === 1 }" @click="setActiveTab(1)">
          Хіти продаж
        </li>
        <li :class="{ active: activeSlide === 2 }" @click="setActiveTab(2)">
          Новинки
        </li>
        <li :class="{ active: activeSlide === 3 }" @click="setActiveTab(3)">
          Акції
        </li>
      </ul>
    </div>
    <div class="product_items">
      <app-product-card
        v-for="(product, idx) in activeSlideList"
        :key="'product' + idx"
        :props-data="product"
      ></app-product-card>
      <div class="mainScreen_banner1-pagination">
        <ul>
          <li class="active"><a href=""></a></li>
          <li><a href=""></a></li>
          <li><a href=""></a></li>
        </ul>
      </div>
    </div>
  </div>
</template>

  <script>
import appProductCard from '../main/app-product-card.vue'
// import VueSlickCarousel from 'vue-slick-carousel'
//   import AppPopularProductsItem from './app-popularProductsItem.vue'
export default {
  components: { appProductCard },
  name: 'ProductSlider',
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
        dots: true,
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
              rows: 2,
              slidesToShow: 2,
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
    grid-template-columns: repeat(4, 1fr);
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
    @include _768 {
      grid-template-columns: repeat(2, 1fr);
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
