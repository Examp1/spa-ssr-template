<template>
    <div class="contacts-tabs">
        <h2>{{ $t('contacts') }}</h2>
        <div class="tabs-head">
            <div
                v-for="(tab, idx) in propsData.contacts"
                :key="idx"
                class="tab"
                :class="{ active: current == idx }"
                @click="current = idx"
            >
                {{ tab.name }}
            </div>
        </div>
        <div class="contact-container">
            <div class="col-l contact-info">
                <div class="item">
                    <div class="title">
                        {{ propsData.contacts[current].title || 'title' }}
                    </div>
                    <a
                        v-for="(phone, index) in propsData.contacts[current]
                            .phones"
                        :key="index"
                        class="link"
                        :href="`tel:${phone.number}`"
                        >{{ phone.number }}</a
                    >
                    <a
                        v-if="propsData.contacts[current].email"
                        class="link"
                        :href="`mailto:${propsData.contacts[current].email}`"
                        >{{ propsData.contacts[current].email }}</a
                    >
                    <span
                        v-if="propsData.contacts[current].address"
                        class="link"
                        >{{ propsData.contacts[current].address }}</span
                    >
                    <div
                        v-for="(item, idx) in propsData.contacts[current]
                            .schedule"
                        :key="`contactCurIdx${idx}`"
                        class="time"
                    >
                        {{ item.label }}: {{ item.time }}
                    </div>
                    <ul
                        v-if="propsData.contacts[current].socials"
                        class="socials"
                    >
                        <li
                            v-for="(item, idx) in propsData.contacts[current]
                                .socials"
                            :key="`contactSocIdx${idx}`"
                        >
                            <a :href="item.link">
                                <img :src="path(item.image)" alt="" />
                            </a>
                        </li>
                        <li
                            v-for="(item, idx) in propsData.contacts[current]
                                .messengers"
                            :key="`contactMsgIdx${idx}`"
                        >
                            <a :href="item.link">
                                <i
                                    class="icon"
                                    :class="{
                                        'icon-telegram':
                                            item.type == 'telegram',
                                        'icon-viber': item.type == 'viber',
                                        'icon-whatsapp':
                                            item.type == 'whats_app',
                                        'icon-messanger':
                                            item.type == 'facebook_messenger',
                                    }"
                                ></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-r">
                <div ref="main-map" class="main-map"></div>
            </div>
        </div>
    </div>
</template>

<script>
// import { mapGetters } from "vuex";
//   import GoogleMapsApiLoader from "google-maps-api-loader";
export default {
    name: 'ContactsTab',
    data() {
        return {
            current: 0,
            google: null,
            map: null,
            markers: [],
            mapConfig: {
                zoom: 7,
                center: { lat: 49.224154, lng: 31.505217 },
            },
        }
    },
    computed: {
        markersData() {
            const list = []
            this.propsData.contacts.forEach((contact) => {
                contact.maps_marks.forEach((marker) => {
                    list.push(marker)
                })
            })
            return list
        },
    },
    // async mounted() {
    //   const googleMapApi = await GoogleMapsApiLoader({
    //     apiKey: this.propsData.api_key,
    //     language: this.$i18n.locale,
    //   });
    //   this.google = googleMapApi;
    //   this.initializeMap();
    // },
    methods: {
        //   initializeMap() {
        //     const mapContainer = this.$refs.main-map;
        //     this.map = new this.google.maps.Map(mapContainer, this.mapConfig);
        //     this.markersData.forEach((marker) => {
        //       const markerObj = new this.google.maps.Marker({
        //         position: {
        //           lat: +marker.lat,
        //           lng: +marker.lng,
        //         },
        //         map: this.map,
        //       });
        //       this.markers.push(markerObj);
        //     });
        //   },
    },
}
</script>

<style lang="scss" scoped>
.contacts-tabs {
    .tabs-head {
        margin-top: 70px;
        display: flex;
        justify-content: flex-start;
        margin-bottom: 45px;
        .tab {
            color: #000;
            margin-right: 20px;
            cursor: pointer;
            &::before {
                content: '';
                position: absolute;
                bottom: 0px;
                height: 2px;
                width: 0%;
                transition: 0.3s;
                background-color: #000;
            }
            &.active {
                color: #000;
                font-weight: 600;
                position: relative;
                &::before {
                    width: 100%;
                }
            }
        }
    }
}
.contact-container {
    display: grid;
    grid-template-columns: 2fr 3fr;
    min-height: 650px;
    @media (max-width: 820px) {
        grid-template-columns: 1fr;
    }
}
.main-map {
    height: 100%;
    @media (max-width: 820px) {
        height: 600px;
    }
}
.socials {
    list-style: none;
    padding-left: 0px;
    display: flex;
    margin-bottom: 0px;
    li {
        width: 39px;
        height: 39px;
        border-radius: 50%;
        transition: 0.3s;
        border: 1px solid rgba(53, 53, 53, 0.35);
        display: flex;
        align-items: center;
        justify-content: center;
        a {
            margin-bottom: 0px;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
            position: static;
        }
        &:not(:last-of-type) {
            margin-right: 7px;
        }
        &:hover {
            border-color: #000;
        }
        .icon {
            font-size: 18px;
        }
    }
}
.contact-info {
    max-width: 294px;
    .item {
        margin-bottom: 20px;
        &:not(:last-of-type) {
            margin-bottom: 70px;
        }
    }
    .title {
        font-size: 20px;
        line-height: 120%;
        color: #000;
        margin-bottom: 15px;
    }
    a {
        display: block;
        font-weight: 400;
        text-decoration: none;
        font-size: 14px;
        line-height: 120%;
        color: #000;
        margin-bottom: 7px;
    }
}
.time,
.link {
    font-style: normal !important;
    font-weight: 400 !important;
    font-size: 14px !important;
    line-height: 120% !important;
    display: block;
    margin-bottom: 7px !important;
}
</style>
