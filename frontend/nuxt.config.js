const isProduction = process.env.NODE_ENV === 'production';
export default {
  ssr: false,
  // Global page headers: https://go.nuxtjs.dev/config-head
  head: {
    title: 'spa-ssr-template',
    htmlAttrs: {
      lang: 'en',
    },
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: '' },
      { name: 'format-detection', content: 'telephone=no' },
    ],
    link: [{ rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }],
  },

  styleResources: {
    scss: [
      '~/assets/scss/mixinsAndVariables/colors.scss',
      '~/assets/scss/mixinsAndVariables/separators.scss',
      '~/assets/scss/mixinsAndVariables/breakpoints.scss'
    ]
  },
  // Global CSS: https://go.nuxtjs.dev/config-css
  css: ['~/assets/scss/main.scss'],

  // Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
  plugins: ['~/mixins/utils.js', '~/mixins/order.js', '~/mixins/seoMixin.js'],

  // Auto import components: https://go.nuxtjs.dev/config-components
  components: true,

  // Modules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
  buildModules: [
    // https://go.nuxtjs.dev/eslint
    '@nuxtjs/eslint-module',
  ],

  // Modules: https://go.nuxtjs.dev/config-modules
  modules: [
    '@nuxtjs/style-resources',
    // https://go.nuxtjs.dev/axios
    '@nuxtjs/axios',
    '@nuxtjs/i18n',
  ],


  // Axios module configuration: https://go.nuxtjs.dev/config-axios
  axios: {
    // Workaround to avoid enforcing hard-coded localhost:3000: https://github.com/nuxt-community/axios-module/issues/308
    baseURL: 'https://test3.owlweb.com.ua/',
  },

  i18n: {
    defaultLocale: 'uk',
    locales: [
      {
        code: 'uk',
        file: 'uk.json',
      },
      {
        code: 'en',
        file: 'en.json',
      },
    ],
    langDir: '~/locales/',
  },

  // Build Configuration: https://go.nuxtjs.dev/config-build
  build: {
    publicPath: isProduction ? 'app/' : '_nuxt/'
  },
  generate: {
    dir: isProduction ? '../public/assets' : 'dist'
  },
  router: {
    middleware: 'isSSR',
    linkActiveClass: 'activeLink',
    // trailingSlash: false
  },
}
