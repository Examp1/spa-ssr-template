import Vue from "vue";
const MEDIA_PATH_PREFIX = 'media/images/original';
const MEDIA_PATH_PREFIX2 = 'storage/media';
const PDF_PREFIX = '/storage/files/';
const MediaDomain = 'https://spa-ssr-template.owlweb.com.ua/';

const utils = {
    props: ["propsData"],
    computed: {
        locale() {
            return this.$i18n.locale === "" ? "uk" : this.$i18n.locale;
        },
        paddingStyle() {
            const obj = {};
            if (this.propsData && this.propsData.bottom_separator !== null)
                obj.paddingBottom = `${this.propsData.bottom_separator}px`;
            if (this.propsData && this.propsData.top_separator !== null)
                obj.paddingTop = `${this.propsData.top_separator}px`;
            return obj;
        },
    },
    methods: {
        lockScroll(withBg = false) {
            if (withBg) {
                const body = document.querySelector('body')
                body.classList.add('overlay')
            } else {
                const body = document.querySelector('body')
                body.classList.add('lock')
            }
        },
        unLockScroll(withBg) {
            if (withBg) {
                const body = document.querySelector('body')
                body.classList.remove('overlay')
            } else {
                const body = document.querySelector('body')
                body.classList.remove('lock')
            }
        },
        path(s, type) {
            if (type) {
                return `${MediaDomain}${MEDIA_PATH_PREFIX2}${s}`;
            } else {
                return `${MediaDomain}${MEDIA_PATH_PREFIX}${s}`;
            }
        },
        pdfPath(s) {
            return `${MediaDomain}${PDF_PREFIX}${s}`;
        },
        isExtUrl(s) {
            if (s.includes("http")) return true;
            else if (s.includes("@")) return true;
            else return false;
        },
    },
    // async mounted() {
    //     try {
    //         await this.$recaptcha.init()
    //     } catch (e) {
    //         console.error(e)
    //     }
    // },
};
Vue.mixin(utils)
