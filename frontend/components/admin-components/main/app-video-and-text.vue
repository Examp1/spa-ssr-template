<template>
    <div class="video-and-text">
        <h2 v-if="propsData.title" v-html="propsData.title"></h2>
        <div class="redactor" v-html="propsData.text"></div>
        <app-btns v-if="propsData.btns" :props-data="propsData.btns"></app-btns>
        <div class="video-wrapper" @click="playVideo">
            <img
                v-if="!isPlaying && !isLoaded"
                :src="path(propsData.image)"
                alt="Video Thumbnail"
            />
            <div v-if="!isPlaying && !isLoaded" class="play-button"></div>
            <transition name="fade">
                <iframe
                    v-if="isPlaying"
                    :src="youtubeEmbedUrl"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen
                    @load="iframeLoaded"
                >
                </iframe>
            </transition>
        </div>
    </div>
</template>

<script>
import appBtns from '../../ui/app-btns.vue'
export default {
    name: 'VideoAndText',
    components: { appBtns },
    data() {
        return {
            isPlaying: false,
            isLoaded: false,
        }
    },
    computed: {
        youtubeEmbedUrl() {
            // Получаем только часть URL до первого символа '&'
            const baseUrl = this.propsData.file.split('&')[0]
            // Заменяем "watch?v=" на "embed/" и добавляем параметр автовоспроизведения
            return baseUrl.replace('watch?v=', 'embed/') + '?autoplay=1'
        },
    },
    methods: {
        iframeLoaded() {
            this.isLoaded = true
        },
        playVideo() {
            this.isPlaying = true
        },
    },
}
</script>

<style lang="scss" scoped>
.video-wrapper {
    position: relative;
    width: 100%;
    height: 0;
    padding-bottom: 56.25%; /* Aspect ratio 16:9 */
    overflow: hidden;
}

.video-wrapper img,
.video-wrapper iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.play-button {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 50px;
    height: 50px;
    background-color: rgba(0, 0, 0, 0.6);
    border-radius: 50%;
    cursor: pointer;
    &:before {
        content: '▶';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 24px;
        color: #fff;
    }
}
</style>
