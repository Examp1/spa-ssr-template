<template>
  <div>about</div>
</template>

<script>
export default {
  async asyncData({ $axios }) {
    const data = await $axios.$get(
      'https://jsonplaceholder.typicode.com/photos'
    )
    return {
      apiData: data,
    }
  },
}
</script>

<style lang="scss" scoped>
</style>
