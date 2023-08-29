<template>
  <div class="sections">
    <section
      v-for="(item, idx) in propsData"
      :key="item.content + idx"
      :style="{ ...backgroundStyle(item.content), order: item.position }"
      class="component"
    >
      <component
        :is="item.component"
        :props-data="item.content"
        :class="[
          containerClass(item.component),
          `mb-${item.content.bottom_separator}`,
          `mt-${item.content.top_separator}`,
        ]"
      >
      </component>
    </section>
  </div>
</template>


<script>
import AppAccordion from './adminComponents/main/app-accordion.vue'
import AppBlocks from './adminComponents/main/app-blocks.vue'
import appFirstScreen from './adminComponents/main/app-firstScreen.vue'
import AppGallery from './adminComponents/main/app-gallery.vue'
import AppImageAndText from './adminComponents/main/app-image-and-text.vue'
import AppNumbers from './adminComponents/main/app-numbers.vue'
import AppSimpleText from './adminComponents/main/app-simple-text.vue'
import AppStages from './adminComponents/main/app-stages.vue'
import AppTextNColumns from './adminComponents/main/app-text-n-columns.vue'
import AppVideoAndText from './adminComponents/main/app-video-and-text.vue'
import AppDinamicForm from './dynamicForm/app-dinamic-form.vue'
import AppOverlay from './ui/app-overlay.vue'

export default {
  components: {
    'first-screen': appFirstScreen,
    'image-and-text': AppImageAndText,
    'text-n-columns': AppTextNColumns,
    AppOverlay,
    AppDinamicForm,
    gallery: AppGallery,
    'simple-text': AppSimpleText,
    'stages': AppStages,
    'numbers': AppNumbers,
    'accordion': AppAccordion,
    'blocks': AppBlocks,
    'video-and-text': AppVideoAndText,
  },
  methods: {
    containerClass(component) {
      if (component === 'gallery') {
        return 'container-fluid'
      } else {
        return 'container'
      }
    },
    backgroundStyle(content) {
      switch (content.background_type) {
        case 'white':
          return { backgroundColor: 'white' }
        case 'transparent':
          return {} // Не применяем стиль, если transparent
        case 'simple':
          return { backgroundColor: content.background }
        default:
          return {} // Для любых других случаев, не применяем стиль
      }
    },
  },
}
</script>

<style lang="scss" scoped>
.sections{
  display: flex;
  flex-direction: column;
}
</style>
