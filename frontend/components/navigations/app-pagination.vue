<template>
    <div class="paginateBlock">
      <button v-if="propsData.last_page !== 1 && !hideBtn" class="button loadMore" @click="$emit('fetchData')">
        {{ $t('more') }}
        <i class="icon icon-loader"></i>
      </button>
      <div v-if="propsData.last_page !== 1" class="paggination">
        <span
          :class="{ disabled: btnPrev === 'disabled' }"
          class="paggination__btn prev icon icon-arrow"
          @click="goPaginate(btnPrev)"
        ></span>
        <ul class="paggination__list">
          <li v-for="(item, idx) in links" :key="idx" class="paggination__item">
            <span
              class="paggination__link"
              :class="{ active: currenPage == item.label }"
              @click="goPaginate(item.label)"
            >
              {{ item.label }}
            </span>
          </li>
        </ul>
        <span
          :class="{ disabled: btnNext === 'disabled' }"
          class="paggination__btn next icon icon-arrow"
          @click="goPaginate(btnNext)"
        ></span>
      </div>
    </div>
  </template>

  <script>
  export default {
    name: 'ThePagination',
    props: {
      hideBtn: {
        type: Boolean,
        default: false,
      },
    },
    computed: {
      btnPrev() {
        return this.currenPage - 1 > 0 ? this.currenPage - 1 : 'disabled'
      },
      btnNext() {
        return this.currenPage + 1 <= this.links.length
          ? this.currenPage + 1
          : 'disabled'
      },
      links() {
        return this.propsData.links.filter((el) => {
          return +el.label
        })
      },
      currenPage() {
        return +this.$route.query.page ? +this.$route.query.page : 1
      },
    },
    mounted() {
      this.$set(this.propsData, this.propsData, this.propsData)
    },
    methods: {
      goPaginate(page) {
        if (this.$route.query.filter) {
          this.$router.push({
            path: this.$router.currentRoute.path,
            query: {
              filter: this.$route.query.filter,
              page,
            },
          })
        } else {
          this.$router.push({
            path: this.$router.currentRoute.path,
            query: {
              page,
            },
          })
        }
      },
    },
  }
  </script>

  <style lang="scss" scoped>
  .paginateBlock {
    margin-top: 90px;
  }
  .paggination,
  .paggination__list {
    display: flex;
    justify-content: center;
    align-items: center;
    grid-gap: 25px;
    span {
      cursor: pointer;
      width: 42px;
      height: 42px;
      border-radius: 50%;
      border: 1px solid $primary;
      display: flex;
      justify-content: center;
      align-items: center;
      color: $primary;
      transition: 0.3s;
      &:hover,
      &.active {
        background-color: $primary;
        color: #fff;
      }
      &.disabled {
        opacity: 0.5;
        pointer-events: none;
      }
    }
    .next {
      transform: rotate(180deg);
    }
  }
  .paggination {
    display: flex;
    @media (max-width: 768px) {
      display: none;
    }
  }
  .loadMore {
    display: none;
    @media (max-width: 768px) {
      display: flex;
      align-items: center;
      justify-content: center;
      .icon {
        margin-left: 10px;
        pointer-events: none;
      }
    }
  }
  </style>
