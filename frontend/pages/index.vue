<template>
  <div class="app">
    <nuxt-link :to="'/about'">about</nuxt-link>
    {{ getMode }}
    <div class="s" @click="slideToggle($event.target)">triger</div>
    <p>content</p>
    <!-- {{ slideToggle }} -->
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import slideMixin from '~/mixins/slideMixin'
export default {
  name: 'IndexPage',
  // async asyncData({$axios}) {
  //   const data = await $axios.$get('/api/page/get-by-slug', {
  //     lang: 'uk',
  //     slug: '/'
  //   })
  //   return {
  //     apiData: data,
  //   }
  // },
  mixins: [slideMixin],
  data() {
    return {
      meta: {
        title: 'OwlWeb Team ►  Розробка сайтів, дизайн та підтримка під ключ',
        description:
          'Професійна розробка сайту ❝С Нуля під Ключ❞ для вашого бізнесу від OwlWeb Team  ➤Корпоративні сайти⚡ Web-сервіси⚡ e-Commerce⚡ Landing Page ✓Техпідтримка!',
        favicon:
          'https://owlweb.com.ua/storage/media/uploads/portfolio/favicon.png',
        og: {
          title: 'OwlWeb Team ►  Розробка сайтів, дизайн та підтримка під ключ',
          description:
            'Професійна розробка сайту ❝С Нуля під Ключ❞ для вашого бізнесу від OwlWeb Team  ➤Корпоративні сайти⚡ Web-сервіси⚡ e-Commerce⚡ Landing Page ✓Техпідтримка!',
          image:
            'https://owlweb.com.ua/storage/media/uploads/home/first screen-1 size.png',
          url: 'https://owlweb.com.ua/home',
          type: 'website',
          locale: 'uk',
          site_name: 'Portfolio',
        },
        links: [
          {
            rel: 'canonical',
            href: 'https://owlweb.com.ua/',
          },
          {
            rel: 'alternate',
            href: 'https://owlweb.com.ua/',
            hreflang: 'uk',
          },
          {
            rel: 'alternate',
            href: 'https://owlweb.com.ua/en',
            hreflang: 'en',
          },
        ],
      },
    }
  },
  computed: {
    ...mapGetters({
      getMode: 'checkMode/getMode',
    }),
  },
  mounted () {
    console.log(this);
  },
}
</script>
<style lang="scss" scoped>
.test {
  width: 100%;
  height: 100vh;
  background-color: #f4f4f4;
}
</style>
