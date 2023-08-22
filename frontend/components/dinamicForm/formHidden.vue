<template>
  <div>
    <input type="text" :name="propsData.name" hidden />
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
  props: {
    itemName: {
      type: String,
      default: '',
    },
  },
  computed: {
    ...mapGetters({
      getItemName: 'form/getItemName',
    }),
  },
  mounted() {
    setTimeout(() => {
      if (this.getItemName) {
        this.$emit('input', this.getItemName)
      } else {
        this.$emit('input', window.location.href)
      }
    }, 1000)
  },
}
</script>

<style lang="scss" scoped>
</style>
