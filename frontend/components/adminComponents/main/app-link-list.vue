<template>
  <section>
    <div v-if="propsData.type !== 'tags'" class="linkList">
      <h2 class="title" v-html="propsData.title"></h2>
      <ul class="links">
        <li
          v-for="(link, idx) in propsData.list"
          :key="'link' + idx"
          class="link"
        >
          <a :href="link.link">{{ link.name }}</a>
          <div class="linkWrp">
            <p v-if="link.date" class="date">{{ link.date }}</p>
            <a v-if="link.file" :href="link.file" download target="_blank">
              завантажити
            </a>
            <a v-if="link.link" :href="link.link" target="_blank">перейти</a>
          </div>
        </li>
      </ul>
      <buttons-tray v-if="propsData.btns" :buttons="propsData.btns" />
    </div>
    <div v-else class="linkList">
      <ul class="tags">
        <li
          v-for="(tag, idx) in propsData.list"
          :key="'link' + idx"
          class="link"
        >
          <a :href="tag.link">{{ tag.name }} <img :src="path(tag.icon)" alt=""></a>
        </li>
      </ul>
    </div>
  </section>
</template>

<script>

export default {
  name: 'LinkList',
  components: {  },
};
</script>

<style lang="scss" scoped>
.linkList {
//   max-width: 723px;
  width: 100%;
  margin-left: auto;
}
.links {
  padding-left: 0px;
  li {
    border-top: 1px solid #0b1f31;
    display: flex;
    padding: 15px 0px;
    justify-content: space-between;
    &:last-of-type {
      border-bottom: 1px solid #0b1f31;
    }
    .linkWrp {
      display: flex;
      align-items: center;
      * {
        // margin-right: 20px;
        margin: 0px 0px 0px 20px;
      }
    }
    a {
      font-weight: 400;
      font-size: 18px;
      text-decoration: none;
      line-height: 120%;
      letter-spacing: -0.01em;
      color: #0e273d;
    }
  }
}
.tags {
  display: flex;
  flex-wrap: wrap;
  list-style: none;
  li {
    margin-right: 15px;
    a {
      font-weight: 500;
      font-size: 15px;
      line-height: 120%;
      text-decoration: none;
      color: #0e273d;
      padding: 10px 20px;
      display: flex;
      align-items: center;
      border: 1px solid #0b1f31;
      border-radius: 50px;
      img{
        width: 20px;
        margin-left: 12px;
        // height: 100%;
      }
    }
  }
}
</style>
