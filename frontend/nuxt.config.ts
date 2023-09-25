export default defineNuxtConfig({
    // SSR можно настроить через server
    // https://v3.nuxtjs.org/docs/usage/ssr

    ssr: false,

    head: {
        title: "spa-ssr-template",
        htmlAttrs: {
            lang: "en"
        },
        meta: [
            { charset: "utf-8" }
            // ... (остальные мета-теги остаются без изменений)
        ],
        link: [{ rel: "icon", href: "/favicon.ico" }]
    },

    css: [
        // '~/assets/scss/main.scss',
        // ... (остальные стили)
    ],

    //   plugins: [
    //     '~/mixins/utils.js',
    //     '~/mixins/seoMixin.js',
    //     '~/plugins/global.js',
    //     '~/filters/index.js'
    //   ],

    buildModules: ["@nuxtjs/eslint-module"],

    // Для модулей может потребоваться проверка на их совместимость с Nuxt 3 или их альтернативы
    //   modules: [
    //     '@nuxtjs/device',
    //     // Style Resources module может потребоваться обновление для Nuxt 3
    //     '@nuxtjs/style-resources',
    // '@nuxtjs/axios',
    //     'cookie-universal-nuxt',
    //     '@nuxtjs/i18n'
    //   ],

    i18n: {
        defaultLocale: "uk",
        locales: [
            {
                code: "uk",
                file: "uk.json"
            },
            {
                code: "en",
                file: "en.json"
            }
        ],
        langDir: "~/locales/"
    },

    build: {
        publicPath: process.env.NODE_ENV === "production" ? "app/" : "_nuxt/"
        // Настройки транспиляции может потребоваться обновить или изменить
    },

    // Для generate и router может потребоваться дополнительная настройка или обновление
    generate: {
        dir: process.env.NODE_ENV === "production" ? "../public/assets" : "dist"
    },

    router: {
        middleware: ["getMenusAndSettings", "toLowerCase"],
        linkActiveClass: "activeLink"
        // И далее ваш код расширения маршрутов...
    }
});
