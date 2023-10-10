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
    <!-- <div>123</div> -->
</template>

<script>
import { mapActions } from 'vuex'
export default {
    name: 'AppBtn',
    methods: {
        ...mapActions({
            setFormData: 'dinamic_form/setFormData',
            openForm: 'modal/openForm',
        }),
        formOpen(form) {
            this.setFormData(form)
            this.openForm(true)
        },
    },
}
</script>

<style lang="scss" scoped></style>
