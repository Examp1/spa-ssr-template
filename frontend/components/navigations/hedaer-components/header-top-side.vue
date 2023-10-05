<template>
  <div class="header_top">
    <div class="container">
      <p>
        {{ getSettings.promobar_text }}
      </p>
      <app-link :to="getSettings.button_link" class="btn btn-main  btn-small">{{ getSettings.button_name }}</app-link>
      <span class="close">&times;</span>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
  name: 'HeaderTopSide',
  computed: {
    ...mapGetters({
      getSettings: 'app_settings/getSettings',
    }),
  },
}
</script>

<style lang="scss" scoped>
.header_top {
  background-color: #000;
  .container {
    min-height: 50px;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 10px 50px;
    @include _640 {
      padding-left: 15px;
    }
  }
  p {
    display: block;
    font-size: 14px;
    font-weight: 700;
    line-height: 17px;
    color: $white;
    margin: 0 15px 0 0;
    @include _991 {
      font-size: 12px;
      line-height: 15px;
      margin-right: 10px;
    }
  }
  .close {
    cursor: pointer;
    color: $white;
    position: absolute;
    right: 15px;
    top: 50%;
    font-size: 20px;
    transform: translate(0, -50%);
  }
}
</style>
