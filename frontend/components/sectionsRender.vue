<template>
  <div>
    <component
      :is="item.component"
      v-for="(item, idx) in propsData"
      :key="'component' + idx"
      :props-data="item.content"
      :class="`mb-${item.content.bottom_separator} mt-${item.content.top_separator}`"
    >
    </component>
    <!-- <template v-for="(item, idx) in propsData">
      {{ item.component }}
      <component :is="dynamicComponent(item.component)" :key="'item'+idx"></component>
    </template> -->
  </div>
</template>

<script>
import appFirstScreen from './adminComponents/main/app-firstScreen.vue'
// import AppFirstScreen from './adminComponents/app-firstScreen.vue'
// const componentMappings = {
//   test: 'article/test-test.vue',
//   'simple-text': 'main/app-simpleText',
//   // ... другие компоненты
// }

export default {
  components: { 'first-screen': appFirstScreen },
  // methods: {
  //   dynamicComponent(componentName) {
  //     const componentPath = componentMappings[componentName]
  //     if (!componentPath) {
  //       throw new Error(`Component ${componentName} not found in mappings.`)
  //     }
  //     return () => import(`~/components/adminComponents/${componentPath}.vue`)
  //   },
  // },
}
</script>

<style lang="scss" scoped>
</style>
