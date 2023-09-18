<template>
  <section
    class="ticker sectionBorder sectionComponent"
    :class="propsData.text_bg_color"
  >
    <!-- <div class="list1" v-if="propsData['list']">
      <marquee class="scroller" truespeed scrolldelay="30">
        <template v-for="(item, index) in propsData['list']">
          <span
            v-if="item.type == 'text'"
            :key="index"
            class="textSegment"
            :style="{ color: propsData.text_color }"
            >{{ item.data }}</span
          >
          <img
            v-if="item.type == 'image'"
            :key="index"
            class="ic right"
            :src="path(item.data)"
            alt=""
          />
        </template>
      </marquee>
    </div>
    <div class="list2" v-if="propsData['list-2']">
      <marquee class="scroller" direction="right">
        <template v-for="(item, index) in propsData['list-2']">
          <span
            v-if="item.type == 'text'"
            :key="index"
            class="textSegment"
            :style="{ color: propsData.text_color }"
            >{{ item.data }}</span
          >
          <img
            v-if="item.type == 'image'"
            :key="index"
            class="ic right"
            :src="path(item.data)"
            alt=""
          />
        </template>
      </marquee>
    </div> -->

    <div class="list1">
      <div class="scroller">
        <template v-for="(item, index) in propsData['list']">
          <span
            v-if="item.type == 'text'"
            :key="index"
            class="textSegment"
            :style="{ color: propsData.text_color }"
            >{{ item.data }}</span
          >
          <img
            v-if="item.type == 'image'"
            :key="index"
            class="ic right"
            :src="path(item.data)"
            alt=""
          />
        </template>
      </div>
    </div>
    <div v-if="propsData['list-2']" class="list2">
      <div class="scroller">
        <template v-for="(item, index) in propsData['list-2']">
          <span
            v-if="item.type == 'text'"
            :key="index"
            class="textSegment"
            :style="{ color: propsData.text_color }"
            >{{ item.data }}</span
          >
          <img
            v-if="item.type == 'image'"
            :key="index"
            class="ic right"
            :src="path(item.data)"
            alt=""
          />
        </template>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: 'AppTicker',
}
</script>

<style lang="scss" scoped>
.ticker {
  width: 100%;
  overflow: hidden;
  user-select: none;
  * {
    user-select: none;
  }
  .list2 {
    margin-top: 27px;
  }
  .list1,
  .list2 {
    display: flex;
    align-items: center;
    justify-content: center;
    // height: 114px;
    position: relative;
    width: 100%;
    white-space: nowrap;
    .scroller {
      & > * + * {
        margin-left: 24px;
      }
      img {
        width: auto;
        height: 114px;
        vertical-align: top;
        border-radius: 100px;
        pointer-events: none;
        @include s-lg {
          height: 90px;
        }
        @include md {
          height: 65px;
        }
        @include sm {
          height: 60px;
        }
      }
      .textSegment {
        font-style: normal;
        font-weight: 500;
        font-size: 88px;
        line-height: 114px;
        letter-spacing: -0.005em;
        text-transform: uppercase;
        color: #111325;
        @include s-lg {
          font-size: 90px;
          line-height: 90px;
        }
        @include md {
          font-size: 65px;
          line-height: 65px;
        }
        @include sm {
          font-size: 60px;
          line-height: 60px;
        }
      }
    }
  }
  .list1 {
    .scroller {
      animation: text-move 60s linear infinite;
    }
  }
  .list2 {
    .scroller {
      animation: text-move-r 60s linear infinite;
    }
  }
}
@keyframes text-move {
  0% {
    transform: translateX(0%);
  }
  50% {
    transform: translateX(-30%);
  }
  100% {
    transform: translateX(0%);
  }
}
@keyframes text-move-r {
  0% {
    transform: translateX(-30%);
  }
  50% {
    transform: translateX(0%);
  }
  100% {
    transform: translateX(-30%);
  }
}
</style>
