<template>
  <div class="sections">
    <first-screen
      v-if="!hasFirstScreen"
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
import AppAccordionTable from './adminComponents/main/app-accordion-table.vue'
import AppAccordion from './adminComponents/main/app-accordion.vue'
import AppAdvantages from './adminComponents/main/app-advantages.vue'
import AppBlocksLinks from './adminComponents/main/app-blocks-links.vue'
import AppBlocks from './adminComponents/main/app-blocks.vue'
import AppCta from './adminComponents/main/app-cta.vue'
import appFirstScreen from './adminComponents/main/app-firstScreen.vue'
import AppFullImage from './adminComponents/main/app-full-image.vue'
import AppGallery from './adminComponents/main/app-gallery.vue'
import AppImageAndText from './adminComponents/main/app-image-and-text.vue'
import AppLinkList from './adminComponents/main/app-link-list.vue'
import AppNumbers from './adminComponents/main/app-numbers.vue'
import AppPartners from './adminComponents/main/app-partners.vue'
import AppSimpleText from './adminComponents/main/app-simple-text.vue'
import AppSimpleTitle from './adminComponents/main/app-simple-title.vue'
import AppStages from './adminComponents/main/app-stages.vue'
import AppTable from './adminComponents/main/app-table.vue'
import AppTeam from './adminComponents/main/app-team.vue'
import AppTextDivider from './adminComponents/main/app-text-divider.vue'
import AppTextNColumns from './adminComponents/main/app-text-n-columns.vue'
import AppTheses from './adminComponents/main/app-theses.vue'
import AppVideoAndText from './adminComponents/main/app-video-and-text.vue'
import AppTicker from './adminComponents/widgets/app-ticker.vue'
import AppDinamicForm from './dynamicForm/app-dinamic-form.vue'
import AppOverlay from './ui/app-overlay.vue'

export default {
  components: {
    'first-screen': appFirstScreen,
    'image-and-text': AppImageAndText,
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
  },
  computed: {
    isMobile() {
      if (typeof window === 'undefined') {
        return false
      }
      const userAgent = window.navigator.userAgent
      return /mobile/i.test(userAgent)
    },
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
    changeCity(city) {
      this.$setCity(city);
    },
    containerClass(component) {
      if (component === 'gallery' || component === 'ticker') {
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
