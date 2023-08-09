<template>
  <div class="test"></div>
</template>

<script>
export default {
  name: 'IndexPage',
  data() {
    return {
      res: null,
    }
  },
  // async asyncData() {
  //   const data = await fetchData('https://jsonplaceholder.typicode.com/comments')
  //   return {
  //     apiData: data,
  //   }
  // },
  // async created() {
  //   const res = await fetchData()
  //   this.res = res
  // },
}
</script>
<style lang="scss" scoped>
.test {
  width: 100%;
  height: 100vh;
  background-color: #f4f4f4;
}
</style>
