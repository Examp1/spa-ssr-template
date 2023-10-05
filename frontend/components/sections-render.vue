<template>
  <div class="sections">
    <first-screen
      v-if="!hasFirstScreen && !homePage"
      :props-data="simpleFisrtScreen"
    ></first-screen>
    <section
      v-for="(item, idx) in propsData.constructor"
      :key="item.content + idx"
      :style="{ ...backgroundStyle(item.content), order: item.position }"
      class="component"
    >
      <component
        :is="item.component"
        v-if="+item.visibility"
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
        <form_component :props-data="getFormData"></form_component>
      </app-overlay>
    </transition>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import AppAccordionTable from './admin-components/main/app-accordion-table.vue'
import AppAccordion from './admin-components/main/app-accordion.vue'
import AppAdvantages from './admin-components/main/app-advantages.vue'
import AppBlocksLinks from './admin-components/main/app-blocks-links.vue'
import AppBlocks from './admin-components/main/app-blocks.vue'
import AppCta from './admin-components/main/app-cta.vue'
import appFirstScreen from './admin-components/main/app-firstScreen.vue'
import AppFullImage from './admin-components/main/app-full-image.vue'
import AppGallery from './admin-components/main/app-gallery.vue'
import AppImageAndText from './admin-components/main/app-image-and-text.vue'
import AppLinkList from './admin-components/main/app-link-list.vue'
import AppNumbers from './admin-components/main/app-numbers.vue'
import AppPartners from './admin-components/main/app-partners.vue'
import AppSimpleText from './admin-components/main/app-simple-text.vue'
import AppSimpleTitle from './admin-components/main/app-simple-title.vue'
import AppStages from './admin-components/main/app-stages.vue'
import AppTable from './admin-components/main/app-table.vue'
import AppTeam from './admin-components/main/app-team.vue'
import AppTextDivider from './admin-components/main/app-text-divider.vue'
import AppTextNColumns from './admin-components/main/app-text-n-columns.vue'
import AppTheses from './admin-components/main/app-theses.vue'
import AppVideoAndText from './admin-components/main/app-video-and-text.vue'
import AppTicker from './admin-components/widgets/app-ticker.vue'
import AppDinamicForm from './dynamicForm/app-dinamic-form.vue'
import AppCategoriesW from './e-com/widgets/app-categories-w.vue'
import AppSeeAlso from './admin-components/widgets/app-see-also.vue'
import AppProductSlider from './e-com/widgets/app-product-slider.vue'
import AppOverlay from './ui/app-overlay.vue'

export default {
  components: {
    'first-screen': appFirstScreen,
    'image-and-text': AppImageAndText,
    'image-video-and-text': AppImageAndText,
    'text-n-columns': AppTextNColumns,
    AppOverlay,
    form_component: AppDinamicForm,
    gallery: AppGallery,
    'simple-text': AppSimpleText,
    stages: AppStages,
    numbers: AppNumbers,
    accordion: AppAccordion,
    blocks: AppBlocks,
    'video-and-text': AppVideoAndText,
    cta: AppCta,
    'blocks-links': AppBlocksLinks,
    theses: AppTheses,
    'text-divider': AppTextDivider,
    advantages: AppAdvantages,
    team: AppTeam,
    table_component: AppTable,
    'simple-title': AppSimpleTitle,
    'full-image': AppFullImage,
    partners: AppPartners,
    'accordion-table': AppAccordionTable,
    'link-list': AppLinkList,
    ticker: AppTicker,
    'categories-w': AppCategoriesW,
    'product-slider': AppProductSlider,
    'see-also': AppSeeAlso
  },

  props: {
    homePage: {
      type: Boolean,
      default: false,
    },
  },

  computed: {
    ...mapGetters({
      getFormData: 'dinamic_form/getFormData',
      isFormOpen: 'modal/isFormOpen',
    }),
    hasFirstScreen() {
      return this.propsData.constructor.some(
        (item) => item.component === 'first-screen' && +item.visibility
      )
      // return temp
    },
    simpleFisrtScreen() {
      return {
        title: this.propsData.translate.title,
        description: this.propsData.translate.description,
        btns: this.propsData.translate.main_screen,
        image: this.propsData.translate.image,
        image_mob: this.propsData.translate.image_mob,
      }
    },
  },
  methods: {
    containerClass(component) {
      if (component === 'ticker') {
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
