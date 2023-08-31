<template>
  <div class="sections">
    <first-screen
      v-if="organizedData.firstScreen"
      :props-data="organizedData.firstScreen.content"
    ></first-screen>
    <section
      v-for="(item, idx) in organizedData.components"
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
    <transition name="fade" mode="out-in">
      <app-overlay v-if="isFormOpen">
        <app-dinamic-form :props-data="getFormData"></app-dinamic-form>
      </app-overlay>
    </transition>
  </div>
</template>


<script>
import { mapGetters } from 'vuex'
import AppAccordion from './adminComponents/main/app-accordion.vue'
import AppBlocks from './adminComponents/main/app-blocks.vue'
import AppCta from './adminComponents/main/app-cta.vue'
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
    stages: AppStages,
    numbers: AppNumbers,
    accordion: AppAccordion,
    blocks: AppBlocks,
    'video-and-text': AppVideoAndText,
    'cta': AppCta,
  },
  computed: {
    ...mapGetters({
      getFormData: 'dinamic_form/getFormData',
      isFormOpen: 'modal/isFormOpen',
    }),
    organizedData() {
      // Находим объект с component: 'first-screen'
      const firstScreenComponent = this.propsData.find(
        (item) => item.component === 'first-screen'
      )

      // Фильтруем все объекты, которые не имеют component: 'first-screen'
      const otherComponents = this.propsData.filter(
        (item) => item.component !== 'first-screen'
      )

      return {
        firstScreen: firstScreenComponent,
        components: otherComponents,
      }
    },
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
          return {}
        case 'simple':
          return { backgroundColor: content.background }
        default:
          return {}
      }
    },
  },
}
</script>

<style lang="scss" scoped>
.sections {
  display: flex;
  flex-direction: column;
}
</style>
