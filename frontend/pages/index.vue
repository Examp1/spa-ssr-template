<template>
  <div class="app">
    <nuxt-link :to="'/about'">about</nuxt-link>
    {{ getMode }}
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
  name: 'IndexPage',
  async asyncData({$axios}) {
    const data = await $axios.$get(
      'https://jsonplaceholder.typicode.com/comments'
    )
    return {
      apiData: data,
    }
  },
  data() {
    return {
      res: null,
    }
  },
  computed: {
    ...mapGetters({
      getMode: 'checkMode/getMode',
    }),
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
