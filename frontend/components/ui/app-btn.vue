<template>
  <app-link
    v-if="propsData.type_link === 'link'"
    class="btn"
    :class="propsData.type"
    :to="propsData.link"
  >
    {{ propsData.text }}
  </app-link>
  <span
    v-else
    :class="propsData.type"
    class="btn"
    @click="formOpen(propsData, 'modal')"
  >
    {{ propsData.text }}
  </span>
</template>

<script>
import { mapActions } from 'vuex'
export default {
  name: 'AppBtn',
  methods: {
    ...mapActions({
      setFormData: 'modals/setFormData',
      setOpenModal: 'modals/setOpenModal',
    }),
    formOpen(form, method) {
      this.setFormData(form)
      this.setOpenModal(method)
    },
  },
}
</script>

<style lang="scss" scoped>

</style>
