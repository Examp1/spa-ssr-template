<template>
  <div class="background-layer" :style="getBackground">
    <div v-if="false" class="noise-container">
      <div class="noise-layer"></div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
  computed: {
    ...mapGetters({
      getBackground: 'app_settings/getBackground',
      getLogo: 'app_settings/getLogo',
    }),
  },
}
</script>

<style>
.background-layer {
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  z-index: -1;
}

.noise-container {
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  overflow: hidden;
}

.noise-layer {
  position: absolute;
  top: -10rem;
  left: -10rem;
  z-index: 100000;
  width: calc(100% + 20rem);
  height: calc(100% + 20rem);
  background-image: url(@/assets/images/noise.png);
  animation: noise 1s infinite;
}

@keyframes noise {
  0% {
    transform: translate3d(0, 9rem, 0);
  }

  10% {
    transform: translate3d(-1rem, -4rem, 0);
  }

  20% {
    transform: translate3d(-8rem, 2rem, 0);
  }

  30% {
    transform: translate3d(9rem, -9rem, 0);
  }

  40% {
    transform: translate3d(-2rem, 7rem, 0);
  }

  50% {
    transform: translate3d(-9rem, -4rem, 0);
  }

  60% {
    transform: translate3d(2rem, 6rem, 0);
  }

  70% {
    transform: translate3d(7rem, -8rem, 0);
  }

  80% {
    transform: translate3d(-9rem, 1rem, 0);
  }

  90% {
    transform: translate3d(6rem, -5rem, 0);
  }

  to {
    transform: translate3d(-7rem, 0, 0);
  }
}
</style>
