<template>
    <div class="partners">
        <h2>{{ propsData.title }}</h2>
        <p>{{ propsData.subtitle }}</p>
        <div class="wrp">
            <app-link
                v-for="(partner, idx) in propsData.list"
                :key="'partner' + idx"
                :to="partner.link"
                class="partner"
            >
                <img
                    :src="path(partner.image)"
                    alt="partner image"
                />
                <div
                    v-if="partner.text"
                    class="desc"
                    v-html="partner.text"
                ></div>
            </app-link>
        </div>
    </div>
</template>

<script>
export default {
    name: 'AppPartners',
}
</script>

<style lang="scss" scoped>
.partners {
    text-align: center;

    p {
        color: var(--black, #1C1518);
        font-size: 20px;
        font-style: normal;
        font-weight: 400;
        line-height: 120%;
        margin-top: 10px;
    }

    a {
        text-decoration: none;
    }

    .desc {
        margin-top: 20px;
        color: var(--black, #1C1518);
        font-size: 20px;
        font-style: normal;
        font-weight: 400;
        line-height: 120%;
    }
}

.wrp {
    margin-top: 30px;
    display: flex;
    grid-gap: 20px;
    justify-content: center;
    flex-wrap: wrap;
    // grid-template-columns: repeat(4, 1fr);
    a {
        width: calc(100% / 4 - 20px);
    }

    @include _1440 {

        // grid-template-columns: repeat(3, 1fr);
        a {
            width: calc(100% / 3 - 20px);
        }
    }

    @include _820 {

        // grid-template-columns: repeat(2, 1fr);
        a {
            width: calc(100% / 2 - 20px);
        }
    }

    @include _640 {

        // grid-template-columns: repeat(1, 1fr);
        a {
            width: 100%;
        }
    }
}</style>
