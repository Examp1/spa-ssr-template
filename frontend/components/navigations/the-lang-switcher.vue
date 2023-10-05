<template>
    <div
        class="langSwitchWrap"
        :class="{ active: active }"
        @click="active = !active"
    >
        <div class="langSwitch">
            <span
                class="holder"
                :class="{ active: $i18n.locale === 'uk' }"
                @click="changeLanguage('uk')"
                >Укр</span
            >
            <span
                class="holder"
                :class="{ active: $i18n.locale === 'en' }"
                @click="changeLanguage('en')"
                >Eng</span
            >
        </div>
    </div>
</template>

<script>
export default {
    name: 'LangSwitch',
    data() {
        return {
            active: false,
            currentLocale: 'Укр',
            otherLocale: 'eng',
        }
    },
    methods: {
        changeLanguage(lang) {
            this.$i18n.setLocale(lang)
        },
    },
}
</script>

<style lang="scss" scoped>
.langSwitchWrap {
    position: relative;
    margin-left: 25px;
    transition: 0.3s;
    &:hover {
        border-color: $text-secondary;
    }
    &.active {
        border-color: $text-secondary;
    }
    .langSwitch {
        border: 2px solid $text-primary;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: 0.3s;
        height: 39px;
        width: 39px;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        .holder {
            font-weight: 500;
            font-size: 11.0769px;
            line-height: 100%;
            color: $text-primary;
            text-align: center;
            letter-spacing: -0.04em;
            text-transform: uppercase;
            text-decoration: none;
            position: absolute;
            z-index: 5;
            transition: 0.3s ease;
            &:not([class='active']) {
                opacity: 0;
                visibility: hidden;
            }
        }
        .holder.active {
            opacity: 1;
            visibility: visible;
        }
        &:hover {
            background-color: $text-secondary;
            .holder {
                &.active {
                    opacity: 0 !important;
                    visibility: hidden !important;
                }
                &:not([class='active']) {
                    opacity: 1;
                    visibility: visible;
                }
            }
        }
    }
}
</style>
