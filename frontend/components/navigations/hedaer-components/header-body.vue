<template>
  <div class="header_body">
    <div class="container">
      <div class="header_body-left">
        <div class="logo">
          <component :is="$route.name.includes('index__') ? 'span' : 'nuxt-link'" to="/">
            <img :src="path(getLogo)" alt="" />
          </component>
        </div>
        <div class="header_catalog">
          <a href="" class="btn btn-main"><i-catalog></i-catalog> Каталог</a>
        </div>
        <div class="header_search">
          <div class="header_search-form">
            <form action="#">
              <input type="text" placeholder="Cтільці для кухні дерев’яні" />
              <input type="submit" value="" />
            </form>
          </div>
          <div class="header_search-nav">
            <ul>
              <li><a href="">Стільці кухонні</a></li>
              <li><a href="">Ліжко двоспальне</a></li>
              <li><a href="">Диван сірий</a></li>
              <li><a href="">Килим круглий</a></li>
            </ul>
          </div>
        </div>
        <div class="header_tel">
          <a href=""
            >+38 (066) 662-90-91
            <i><img src="img/icon-arrow-bot-black.svg" alt="img" /></i
          ></a>
        </div>
      </div>
      <div class="header_icons">
        <div class="header_icon">
          <a href=""><i-user></i-user></a>
        </div>
        <div class="header_icon">
          <a href=""
            ><p>10</p>
            <i-heart></i-heart>
          </a>
        </div>
        <div class="header_icon header_icon-cart">
          <a href=""
            ><p>3</p>
            <i-cart></i-cart>
        </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import ICart from '../../icons/i-cart.vue'
import iCatalog from '../../icons/i-catalog.vue'
import IHeart from '../../icons/i-heart.vue'
import IUser from '../../icons/i-user.vue'
export default {
  components: { iCatalog, IUser, IHeart, ICart },
  name: 'HeaderBody',
  computed: {
    ...mapGetters({
      getLogo: 'app_settings/getLogo',
    }),
  },
}
</script>

<style lang="scss" scoped>
.header {
  &_body {
    padding-top: 30px;
    padding-bottom: 42px;
    border-bottom: 1px solid #d9d9d9;
    margin-bottom: 30px;
    @include _991 {
      padding-bottom: 30px;
      margin: 0;
    }
    .container {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    &-left {
      display: flex;
      align-items: center;
    }
  }
  &_catalog {
    margin-right: 15px;
    @include _480 {
      margin-right: 10px;
    }
  }
  &_search {
    position: relative;
    margin-right: 20px;
    &-form {
      position: relative;
      input[type='text'] {
        display: block;
        border-radius: 5px;
        background-color: #f1f1f1;
        @include size(429px, 50px);
        border: none;
        padding: 0 15px 0 45px;
        font-family: Calibri;
        font-size: 14px;
        font-weight: 400;
        line-height: 17px;
        color: #9e9e9e;
         transition: 0.3s;
        &:hover {
          background-color: #f5f5f5;
        }
        @include _991 {
          display: none;
        }
      }
      input[type='submit'] {
        display: block;
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translate(0, -50%);
        // background: url(../img/icon-search.svg) no-repeat;
        @include size(20px);
        cursor: pointer;
        padding: 0;
        border: none;
        @include _991 {
          position: static;
          @include size(40px);
          border-radius: 5px;
          background-color: #f1f1f1;
          background-position: 50% 50%;
          transform: translate(0, 0);
        }
      }
    }
    &-nav {
      position: absolute;
      bottom: -22px;
      left: 0;
      ul {
        display: flex;
      }
      li {
        margin: 0 10px 0 0;
      }
      a {
        display: block;
        font-family: Calibri;
        font-size: 14px;
        line-height: 17px;
        color: #9e9e9e;
         transition: 0.3s;
        text-decoration: none;
        position: relative;
        &:after {
          @include after;
          position: absolute;
           transition: 0.3s;
          bottom: 1px;
          left: 0;
          width: 100%;
          height: 1px;
          background-color: #9e9e9e;
        }
        &:hover:after {
          width: 0;
        }
      }
    }
    @include _991 {
      margin: 0;
      &-nav {
        display: none;
      }
    }
  }
  &_tel {
    @include _1280 {
      display: none;
    }
    a {
      display: flex;
      align-items: center;
      text-decoration: none;
      color: #000;
      font-family: Calibri;
      font-size: 24px;
      font-weight: 700;
      line-height: 29px;
       transition: 0.3s;
      i {
        display: block;
        margin-left: 2px;
        position: relative;
      }
      img {
        display: block;
         transition: 0.3s;
        position: relative;
        top: 0;
      }
    }
  }
  &_icons {
    display: flex;
    align-items: center;
  }
  &_icon {
    margin-left: 15px;
    &:first-child {
      margin: 0;
    }
    a {
      display: block;
      position: relative;
      img {
        display: block;
         transition: 0.3s;
        opacity: 1;
        &:hover {
          opacity: 0.5;
        }
      }
      p {
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        position: absolute;
        z-index: 2;
        @include size(17px);
        border-radius: 100px;
        color: #000;
        font-family: Calibri;
        font-size: 10px;
        font-weight: 400;
        line-height: 12px;
        right: -6px;
        top: -6px;
        background-color: #fff101;
      }
    }
    &-cart {
      a p {
        background-color: #feca0a;
      }
    }
    @include _991 {
      display: none;
      &-cart {
        display: block;
        margin: 0;
      }
    }
  }
}
.logo {
  margin-right: 35px;
  a {
    display: block;
    img {
      @include size(208px, 60px);
      @include _991 {
        @include size(159px, 52px);
        background-size: cover;
      }
      @include _480 {
        @include size(113px, 37px);
      }
    }
  }
  @include _640 {
    margin-right: 25px;
  }
  @include _480 {
    margin-right: 20px;
  }
}
</style>
