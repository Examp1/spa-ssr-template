<template>
  <section class="formAdapter">
    <modal-form v-if="blockType === 'modal'"></modal-form>
    <block-form
      v-else-if="blockType === 'block'"
      :propsData="propsData"
    ></block-form>
  </section>
</template>

<script>
import BlockForm from '../dinamicForm/BlockForm.vue'
import ModalForm from '../dinamicForm/ModalForm.vue'
export default {
  name: 'FormAdapter',
  components: { ModalForm, BlockForm },
  props: {
    formId: {
        type: Number,
        default: null
    },
    formText: {
        type: String,
        default: null
    },
  },
  computed: {
    blockType() {
    //   if (this.propsData && this.propsData.length) {
        return 'block'
    //   } else {
        // return 'modal'
    //   }
    },
  },
}
</script>

<style lang="scss" scoped>
</style>
