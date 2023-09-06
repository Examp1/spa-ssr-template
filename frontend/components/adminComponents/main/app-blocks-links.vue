<template>
  <div class="blocks-links">
    <h2>{{ propsData.title }}</h2>
    <div class="links">
      <div
        v-for="(link, idx) in propsData.list"
        :key="'link' + idx"
        class="link"
      >
        <p class="title">{{ link.title }}</p>
        <span class="subtitle">{{ link.subtitle }}</span>
        <div class="socials">
          <a v-if="link.link_blocks_link_1" :href="link.link_blocks_link_1"> <img :src="path(link.link_blocks_image_1)" alt=""></a>
          <a v-if="link.link_blocks_link_2" :href="link.link_blocks_link_2"> <img :src="path(link.link_blocks_image_2)" alt=""></a>
          <a v-if="link.link_blocks_link_3" :href="link.link_blocks_link_3"> <img :src="path(link.link_blocks_image_3)" alt=""></a>
          <a v-if="link.link_blocks_link_4" :href="link.link_blocks_link_4"> <img :src="path(link.link_blocks_image_4)" alt=""></a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'BlocksLinks',
}
</script>

<style lang="scss" scoped>
.links {
  display: grid;
  grid-gap: 40px;
  grid-template-columns: repeat(4, 1fr);
  @include s-lg{
    grid-template-columns: repeat(3, 1fr);
  }
  @include sm{
    grid-template-columns: repeat(2, 1fr);
  }
  @include m-sm{
    grid-template-columns: 1fr;
  }
  .link {
    padding: 36px;
    border: 1px solid #000;
    border-radius: 30px;
    text-align: center;
    .title {
      font-size: 16px;
      font-style: normal;
      font-weight: 600;
      line-height: 130%;
      text-transform: uppercase;
      margin-bottom: 5px;
    }
    .subtitle {
      text-align: center;
      font-size: 15px;
      font-style: normal;
      font-weight: 300;
      line-height: 125%;
    }
  }
  .socials{
    display: flex;
    justify-content: center;
    grid-gap: 15px;
    margin-top: 30px;
  }
  img{
    width: 24px;
  }
}
</style>
