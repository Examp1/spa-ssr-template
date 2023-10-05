<template>
    <div class="header_nav container">
        <ul>
            <li v-for="(li, idx) in getHeaderMenu" :key="'li' + idx">
                <app-link :to="li.url">{{ li.name }} <i v-if="li.children.length" class="icon icon-arrow-down"></i>
                </app-link>
                <!-- <ul v-if="li.children.length">
          <li
            v-for="(childrenLi, idx2) in li.children"
            :key="'childrenLi' + idx2"
          >
            <app-link :to="childrenLi.url">{{ childrenLi.name }}</app-link>
          </li>
        </ul> -->
            </li>
        </ul>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
    name: 'HeaderNav',
    computed: {
        ...mapGetters({
            getLogo: 'app_settings/getLogo',
            getHeaderMenu: 'menu/getHeaderMenu',
        }),
    },
}
</script>

<style lang="scss" scoped>
.header_nav {
    @include _991 {
        display: none;
    }

    ul {
        display: flex;
        grid-gap: 70px;
    }

    li {
        &.activeLink {
            font-family: Calibri;
            font-size: 16px;
            font-weight: 700;
            line-height: 20px;
            color: #de2b2b;
        }
    }

    a {
        display: flex;
        align-items: center;
        text-decoration: none;
        color: #000;
        font-family: Calibri;
        font-size: 16px;
        font-weight: 400;
        transition: 0.3s;
        line-height: 20px;

        i {
            display: block;
            margin-left: 5px;
            position: relative;
            font-size: 11px;
            transition: 0.3s;
        }

        &:hover {
            color: #feca0a;

            i {
                color: #feca0a
            }
        }
    }

    @include _1280 {
        li {
            margin-right: 25px;
        }
    }
}
</style>
