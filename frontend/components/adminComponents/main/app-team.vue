<template>
  <div class="team">
    <h2 v-if="propsData.title" v-html="propsData.title"></h2>
    <div class="person-list">
      <div
        v-for="(person, idx) in propsData.list"
        :key="'person' + idx"
        class="person"
      >
        <img :src="path(person.image)" alt="" />
        <h3>{{ person.name }}</h3>
        <p>{{ person.position }}</p>
        <span @click="openMoreInfo(person)">Деталі</span>
      </div>
    </div>
    <app-overlay v-if="isOpen" @close="isOpen = false">
      <div class="person-modal">
        <img :src="path(moreInfoAboutPerson.image)" alt="" />
        <div class="text">
          <h3>{{ moreInfoAboutPerson.name }}</h3>
          <p>{{ moreInfoAboutPerson.position }}</p>
          <div class="redactor" v-html="moreInfoAboutPerson.text"></div>
        </div>
      </div>
    </app-overlay>
  </div>
</template>

<script>
import appOverlay from '../../ui/app-overlay.vue'
export default {
  components: { appOverlay },
  name: 'AppTeam',
  data() {
    return {
      isOpen: false,
      moreInfoAboutPerson: null,
    }
  },
  methods: {
    openMoreInfo(person) {
      this.isOpen = true
      this.moreInfoAboutPerson = person
    },
  },
}
</script>

<style lang="scss" scoped>
.person-list {
  display: grid;
  grid-gap: 30px 10px;
  grid-template-columns: repeat(4, 1fr);
  @include s-lg {
    grid-template-columns: repeat(3, 1fr);
  }
  @include sm {
    grid-template-columns: repeat(2, 1fr);
  }
  .person {
    h3 {
      font-size: 16px;
      font-style: normal;
      font-weight: 600;
      line-height: 110%;
      margin: 5px 0px;
    }
    p,
    span {
      margin: 0px;
      font-size: 14px;
      font-style: normal;
      font-weight: 400;
      line-height: 110%;
    }
    span {
      display: block;
      margin-top: 5px;
    }
  }
}
.modal-overlay::v-deep {
  .modal-content {
    max-width: 680px;
    width: 100%;
    padding: 60px 30px;
    .person-modal {
      display: flex;
      grid-gap: 30px;
      img {
        width: 140px;
      }
      h3 {
        font-size: 22px;
        font-style: normal;
        font-weight: 500;
        line-height: 120%;
        letter-spacing: -0.22px;
        margin-bottom: 5px;
      }
      p {
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 120%;
        letter-spacing: -0.16px;
        margin-top: 0px;
        margin-bottom: 15px;
      }
      div::v-deep > * {
        font-size: 14px;
        font-style: normal;
        font-weight: 400;
        line-height: 140%;
        margin-bottom: 10px;
      }
    }
  }
}
</style>
