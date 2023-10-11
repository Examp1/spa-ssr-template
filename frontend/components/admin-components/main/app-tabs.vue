<template>
    <div class="tabs">
        <h2>{{ propsData.title }}</h2>
        <div
            class="desc"
            v-html="propsData.text"
        ></div>
        <div
            class="tab-content"
            v-html="propsData.list[curretnTab].text"
        >
        </div>
        <div class="tabs-ankers">
            <div
                v-for="(tab, idx) in propsData.list"
                :key="'tab' + idx"
                class="anker btn"
                :class="checkCurrent(idx)"
                @click="curretnTab = idx"
            >
                {{ tab.title }}
                <i class="icon icon-big-arrow"></i>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'TabsSection',
    data() {
        return {
            curretnTab: 0
        }
    },
    methods: {
        checkCurrent(i) {
            return this.curretnTab === i ? 'fill' : 'stroke'
        }
    },
}
</script>

<style lang="scss" scoped>
.tabs {
    text-align: center;
}

h2 {
    margin-bottom: 30px;
}

.desc {
    padding-bottom: 30px;
}

.tab-content {
    margin-top: 50px;
    position: relative;
    width: 100%;
    height: 0;
    padding-bottom: 56.25%;
    /* Aspect ratio 16:9 */
    overflow: hidden;
}

.tab-content::v-deep iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.tabs-ankers {
    margin-top: 50px;
    display: flex;
    justify-content: center;
    align-items: center;

    .anker {
        border-radius: unset;
        padding: 20px 60px 20px 30px;
        &:hover{
            color: inherit !important;
            background-color: inherit !important;
        }

        &:first-of-type {
            border-radius: 10px 0px 0px 10px;
        }

        &:last-of-type {
            border-radius: 0px 10px 10px 0px;
        }

    }
}</style>
