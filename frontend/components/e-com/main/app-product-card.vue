<template>
  <div class="product_item">
    <div class="product_img">
      <img :src="previewPhoto" :alt="propsData.alt" />
      <a href="" class="product_like"></a>
      <div class="product_img-info">
        <div v-if="discont" class="product_interest">-{{ discont }}%</div>
        <div class="product_hit">Хіт продаж</div>
        <div class="product_new">Новинка</div>
      </div>
      <div class="product_img-pagination">
        <ul>
          <li
            v-for="i in 5"
            :key="i"
            @click="
              changePhoto(
                'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR-dOSGgDroeOCJszEWHvc3KxtUXzMvpDApBkElS9hx&s'
              )
            "
          >
            <img
              src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR-dOSGgDroeOCJszEWHvc3KxtUXzMvpDApBkElS9hx&s"
              alt="img"
            />
          </li>
        </ul>
      </div>
    </div>
    <div class="product_content">
      <div class="product_content-block">
        <div class="product_item-inStock"><i></i> В наявності</div>
        <div class="product_item-title">{{ propsData.name }}</div>
        <div class="product_item-reviews">
          <div class="product_item-reviews_stars">
            <ul>
              <li v-for="star in 5" :key="'star' + star" class="active">
                <a href=""></a>
              </li>
            </ul>
          </div>
          <a class="numberReviews" href="">19 відгуків</a>
        </div>
      </div>
      <div class="product_content-price">
        <div class="product_content-price_block">
          <span v-if="propsData.old_price">{{ propsData.old_price }}₴</span>
          <strong>{{ propsData.price }}₴</strong>
        </div>
        <a href="" class="btn btn-buy">Купити</a>
      </div>
      <div class="product_content-container">
        <div class="product_content-options">
          <div class="product_content-option">
            <span>Ширина</span>
            <b>120 см</b>
          </div>
          <div class="product_content-option">
            <span>Висота</span>
            <b>76 см</b>
          </div>
          <div class="product_content-option">
            <span>Глибина</span>
            <b>80 см</b>
          </div>
        </div>
        <div class="product_content-installmentPlan">
          <ul>
            <li><img src="img/icon-bank.svg" alt="img" /></li>
            <li><img src="img/icon-bank2.svg" alt="img" /></li>
          </ul>
          <p>Оплата частинами від 1899 ₴/міс</p>
        </div>
      </div>
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
  computed: {
    discont() {
      if (!this.propsData.old_price) return
      return (
        100 -
        (this.propsData.price * 100) / this.propsData.old_price
      ).toFixed(0)
    },
  },
  methods: {
    changePhoto(newPath) {
      this.previewPhoto = newPath
    },
  },
}
</script>

<style lang="scss" scoped>
.product {
  &_item {
    padding: 0 10px;
    @include transition;
    border-radius: 10px;
    &:hover {
      .product_like:after {
        opacity: 0;
      }
      .product_like:before {
        opacity: 1;
      }
      .product_img {
        border-color: transparent;
      }
      box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
      .product_img-pagination {
        opacity: 1;
        pointer-events: all;
      }
      .product_img-pagination img {
        border: none;
      }
      .product_content-price a.btn-buy {
        opacity: 1;
        pointer-events: all;
      }
      .product_content-container {
        max-height: 1000000px;
        opacity: 1;
        pointer-events: all;
      }
    }
    img {
      display: block;
      width: 100%;
      height: auto;
      max-height: 345px;
      object-fit: cover;
    }
    &-inStock {
      color: #000;
      font-family: Calibri;
      font-size: 12px;
      font-weight: 400;
      line-height: 15px;
      display: flex;
      align-items: center;
      margin-bottom: 5px;
      i {
        display: block;
        margin-right: 5px;
        background-color: #00a046;
        border-radius: 3px;
        @include size(10px);
      }
    }
    &-title {
      display: block;
      font-family: Calibri;
      font-size: 16px;
      font-weight: 400;
      line-height: 20px;
      @include _991 {
        font-size: 14px;
        line-height: 17px;
      }
    }
    &-reviews {
      padding-top: 5px;
      display: flex;
      align-items: center;
      a.numberReviews {
        display: block;
        font-family: Calibri;
        font-size: 12px;
        font-weight: 400;
        color: #437cb1;
        text-decoration: none;
      }
      &_stars {
        ul {
          display: flex;
          margin-right: 4px;
        }
        li {
          margin-right: 1px;
          &.active a {
            &:after {
              opacity: 0;
            }
            &:before {
              opacity: 1;
            }
          }
        }
        a {
          display: block;
          @include size(15px);
          position: relative;
          &:after {
            @include after;
            position: absolute;
            top: 0;
            left: 0;
            @include size(100%);
            opacity: 1;
            @include transition;
            // background: url(../img/star-gray.svg) no-repeat;
          }
          &:before {
            @include after;
            position: absolute;
            top: 0;
            left: 0;
            @include size(100%);
            opacity: 0;
            @include transition;
            // background: url(../img/star-yellow.svg) no-repeat;
          }
          &:hover {
            &:after {
              opacity: 0;
            }
            &:before {
              opacity: 1;
            }
          }
        }
      }
    }
    @include _991 {
      padding: 0 5px;
      &:hover {
        box-shadow: none;
        .product_img {
          border: 1px solid #eeeeee;
        }
        .product_like:after {
          opacity: 1;
        }
        .product_like:before {
          opacity: 0;
        }
      }
    }
  }
  &_img {
    position: relative;
    margin-bottom: 20px;
    @include transition;
    overflow: hidden;
    border: 1px solid #eeeeee;
    border-radius: 10px;
    &-pagination {
      position: absolute;
      bottom: 20px;
      left: 25px;
      right: 25px;
      @include transition;
      opacity: 0;
      pointer-events: none;
      ul {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        grid-gap: 2.5px;
        width: 100%;
        justify-content: center;
      }
      li {
        width: 45px;
        height: 45px;
        cursor: pointer;
        display: block;
        border-radius: 5px;
        border: 1px solid #eeeeee;
        overflow: hidden;
        @include transition;
        &:hover {
          border-color: #000;
        }
      }
      img {
        display: block;
        width: 100%;
        object-fit: cover;
        height: 100%;
        border-radius: 0px;
        border: none;
      }
      @include _991 {
        left: 15px;
        right: 15px;
        bottom: 15px;
        opacity: 1;
        pointer-events: all;
        li {
          margin: 0 1px;
        }
        a {
          border-radius: 3px;
        }
      }
    }
    &-info {
      position: absolute;
      top: 15px;
      left: 15px;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
    }
    @include _991 {
      margin-bottom: 10px;
    }
  }
  &_interest {
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    margin-bottom: 10px;
    padding: 0 10px;
    height: 23px;
    border-radius: 3px;
    background-color: #fff101;
    color: #000;
    font-family: Calibri;
    font-size: 14px;
    font-weight: 400;
    @include _991 {
      padding: 0 3px;
      height: 18px;
      margin-bottom: 3px;
      font-size: 10px;
    }
  }
  &_hit {
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    margin-bottom: 10px;
    padding: 0 10px;
    height: 23px;
    border-radius: 3px;
    background-color: #feca0a;
    color: #000;
    font-family: Calibri;
    font-size: 14px;
    font-weight: 400;
    @include _991 {
      padding: 0 3px;
      height: 18px;
      margin-bottom: 3px;
      font-size: 10px;
    }
  }
  &_new {
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    margin-bottom: 10px;
    padding: 0 10px;
    height: 23px;
    border-radius: 3px;
    background-color: #00a046;
    color: #fff;
    font-family: Calibri;
    font-size: 14px;
    font-weight: 400;
    @include _991 {
      padding: 0 3px;
      height: 18px;
      font-size: 10px;
    }
  }
  &_content {
    padding: 0 10px 20px 10px;
    position: relative;
    &-container {
      @include transition;
      opacity: 0;
      pointer-events: none;
      max-height: 0;
      box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
      background: #fff;
      position: absolute;
      left: -10px;
      right: -10px;
      padding: 0 10px 20px;
      border-radius: 0 0 10px 10px;
      z-index: 2;
      top: 100%;
      &:after {
        @include after;
        position: absolute;
        width: 100%;
        height: 20px;
        background-color: #fff;
        top: -20px;
        left: 0;
        right: 0;
      }
      @include _991 {
        display: none;
      }
    }
    &-block {
      margin-bottom: 20px;
    }
    &-price {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      a.btn-buy {
        opacity: 0;
        pointer-events: none;
        background-color: #000000;
        height: 40px;
        padding: 0 23px;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: Calibri;
        font-size: 16px;
        font-weight: 700;
        &:hover {
          background-color: #feca0a;
          color: #000;
        }
        @include _991 {
          display: none;
        }
      }
      &_block {
        span {
          display: block;
          font-family: Calibri;
          font-size: 16px;
          font-weight: 400;
          line-height: 20px;
          color: #959595;
          text-decoration: line-through;
        }
        strong {
          display: block;
          color: #000;
          font-family: Calibri;
          font-size: 24px;
          font-weight: 700;
          line-height: 29px;
        }
        @include _991 {
          span {
            font-size: 14px;
            line-height: 17px;
          }
          strong {
            font-size: 20px;
            line-height: 24px;
          }
        }
      }
    }
    &-options {
      border-top: 1px solid #d9d9d9;
      border-bottom: 1px solid #d9d9d9;
      padding: 10px 0;
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 10px;
    }
    &-option {
      span {
        display: block;
        font-family: Calibri;
        font-size: 14px;
        font-weight: 400;
        line-height: 17px;
        color: #959595;
      }
      b {
        display: block;
        font-family: Calibri;
        font-size: 16px;
        font-weight: 400;
        line-height: 20px;
        color: #000;
      }
    }
    &-installmentPlan {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      ul {
        display: flex;
        align-items: center;
      }
      li {
        margin-right: 10px;
      }
      img {
        display: block;
      }
      p {
        display: block;
        color: #000;
        font-family: Calibri;
        font-size: 16px;
        font-weight: 400;
        line-height: 20px;
        text-align: right;
        max-width: 50%;
      }
    }
    @include _991 {
      padding-bottom: 0;
    }
  }
}
</style>
