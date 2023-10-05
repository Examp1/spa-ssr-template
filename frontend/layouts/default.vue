<template>
    <div class="app">
        <the-header></the-header>
        <nuxt />
        <the-footer></the-footer>
        <background-layer></background-layer>
    </div>
</template>

<script>
import Lenis from '@studio-freight/lenis'
import TheHeader from '../components/navigations/the-header.vue'
import BackgroundLayer from '../components/ui/BackgroundLayer.vue'
import TheFooter from '../components/navigations/the-footer.vue'
export default {
    components: { BackgroundLayer, TheHeader, TheFooter },
    mounted() {
        const lenis = new Lenis({
            duration: 2,
        })

        lenis.on('scroll', (e) => {})

        function raf(time) {
            lenis.raf(time)
            requestAnimationFrame(raf)
        }

        requestAnimationFrame(raf)
    },
}
</script>

<style lang="scss" scoped>
// .page-enter-active {
//     animation: bounce-in 0.8s;
// }
// .page-leave-active {
//     animation: bounce-out 0.5s;
// }

// @keyframes bounce-in {
//     0% {
//         opacity: 0;
//         transform: translateX(-100%);
//     }
//     100% {
//         opacity: 1;
//         transform: translateX(0%);
//     }
// }
// @keyframes bounce-out {
//     0% {
//         opacity: 1;
//         transform: translateX(0%);
//     }
//     100% {
//         opacity: 0;
//         transform: translateX(-100%);
//     }
// }
</style>
