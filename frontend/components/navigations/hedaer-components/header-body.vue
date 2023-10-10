<template>
    <div class="header_body">
        <div class="container">
            <div class="header-logo">
                <component
                    :is="$route.name.includes('index__') ? 'span' : 'nuxt-link'"
                    to="/"
                >
                    <img
                        :src="path(getLogo)"
                        alt=""
                    />
                </component>
            </div>
            <ul class="header-nav">
                <li
                    v-for="(li, idx) in getHeaderMenu"
                    :key="'li' + idx"
                >
                    <app-link :to="li.url">{{ li.name }} <i
                            v-if="li.children.length"
                            class="icon icon-arrow-down"
                        ></i>
                    </app-link>
                    <ul
                        v-if="li.children.length"
                        class="dropdown"
                    >
                        <li
                            v-for="(childrenLi, idx2) in li.children"
                            :key="'childrenLi' + idx2"
                        >
                            <app-link :to="childrenLi.url">{{ childrenLi.name }}</app-link>
                        </li>
                    </ul>
                </li>
            </ul>
            <div class="header_search">
                <div class="header_search-form">
                    <form action="#">
                        <input
                            type="text"
                            placeholder="Cтільці для кухні дерев’яні"
                        >
                        <input
                            type="submit"
                            value=""
                        >
                    </form>
                </div>
            </div>
            <div class="header-socials">социалки</div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
    name: 'HeaderBody',
    computed: {
        ...mapGetters({
            getLogo: 'app_settings/getLogo',
            getHeaderMenu: 'menu/getHeaderMenu',
        }),
    },
}
</script>

<style lang="scss" scoped>
.container {
    padding: 25px 0px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.header-logo img {
    height: 40px;
}

.header-nav {
    display: flex;
    grid-gap: 25px;

    a {
        color: var(--Black, #000);
        font-family: Calibri;
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
        text-decoration: none;
        @include transition;

        &:hover {
            color: $text-blue
        }

        .icon {
            font-size: 10px;
        }
    }

    li {
        position: relative;

        .dropdown {
            z-index: 100;
            position: absolute;
            top: 30px;
            left: 0;
            min-width: 280px;
            width: 100%;
            max-width: 400px;
            padding: 30px 20px;
            @include transition;
            opacity: 0;
            visibility: hidden;
        }

        &:hover .dropdown {
            opacity: 1;
            visibility: visible;
        }
    }
}

.header_search {
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
}
</style>
