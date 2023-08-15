<template>
    <div>
        <template>
        </template>
    </div>
</template>

<script>

export default {
    name: 'Home',
    components: {},
    data() {
        return {
            isDesktop: true
        }
    },
    methods: {
        resizeWindow() {
            if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) || window.innerWidth <= 1280) {
                this.isDesktop = false
            } else {
                this.isDesktop = true
            }
        }
    },
    mounted () {
        window.addEventListener('resize', () => {
            this.resizeWindow()
        });
        this.resizeWindow();
    },
}
</script>
<style lang="sass" scoped>

</style>
