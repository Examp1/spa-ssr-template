<template>
  <div class="category_item">
    <nuxt-link :to="propsData.url">
      <img :src="path(propsData.img)" :alt="propsData.name" />
      <p>{{ propsData.name }}</p>
    </nuxt-link>
  </div>
</template>

<script>
export default {
    name: 'CategoryItem'
}
</script>

<style lang="scss" scoped>
.category_item {
  padding: 0 10px;
  a {
    display: block;
    border: 1px solid #eeeeee;
    border-radius: 5px;
    overflow: hidden;
    text-decoration: none;
    color: #000;
    @include transition;
    &:hover {
      box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
    }
  }
  img {
    display: block;
    width: 100%;
    height: auto;
    max-height: 223px;
    object-fit: cover;
  }
  p {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 15px;
    min-height: 60px;
    text-align: center;
    text-decoration: none;
    font-family: Calibri;
    font-size: 20px;
    font-weight: 400;
    line-height: 24px;
    color: #000;
  }
  @include _991 {
    padding: 0 5px;
    margin-bottom: 10px;
    p {
      font-size: 16px;
      line-height: 19px;
    }
  }
  @include _640 {
    p {
      min-height: 50px;
    }
  }
  @include _480 {
    p {
      min-height: 50px;
    }
  }
}
</style>
