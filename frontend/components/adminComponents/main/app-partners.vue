<template>
  <div class="partners">
    <app-link v-for="(partner, idx) in propsData.list" :key="'partner'+idx" :to="partner.link" class="partner">
      <img :src="path(partner.image)" alt="partner image">
    </app-link>
  </div>
</template>

<script>
  export default {
    name: 'AppPartners'
  }
</script>

<style lang="scss" scoped>
.partners{
  display: grid;
  grid-gap: 20px;
  grid-template-columns: repeat(4, 1fr);
  @include lg{
    grid-template-columns: repeat(3, 1fr);
  }
  @include md{
    grid-template-columns: repeat(2, 1fr);
  }
  @include sm{
    grid-template-columns: repeat(1, 1fr);
  }
}
</style>
