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

    //   dir: {
    //     public: process.env.NODE_ENV === 'production' ? '../public/assets' : 'dist'
    //   },
    //   buildDir: process.env.NODE_ENV === 'production' ? '../public/assets/app/' : '.nuxt/',
    build: {},

    router: {
        // middleware: ['getMenusAndSettings', 'toLowerCase'],
        // linkActiveClass: 'activeLink',
        // И далее ваш код расширения маршрутов...
    }
});
