<template>
  <footer>
    <div class="container">
      <p class="copy">{{ getSettings.copyright }}</p>
      <a href="https://owlweb.com.ua/" target="_blank" rel="nofollow">
        Created by
        <svg
          width="30"
          height="14"
          version="1.0"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 226.000000 141.000000"
          preserveAspectRatio="xMidYMid meet"
        >
          <g
            transform="translate(0.000000,141.000000) scale(0.100000,-0.100000)"
            fill="#DFE2F5"
            stroke="none"
          >
            <path
              d="M334 1218 l155 -163 -78 -20 c-100 -27 -178 -74 -250 -150 -94 -101
                -130 -196 -131 -341 0 -193 101 -354 279 -444 78 -39 154 -53 253 -48 74 4 95
                9 169 46 96 48 224 141 330 241 l74 68 74 -69 c108 -100 234 -192 330 -240 74
                -37 95 -42 169 -46 99 -5 175 9 253 48 178 90 279 251 279 444 -1 153 -38 244
                -145 352 -77 77 -157 123 -257 145 l-58 12 156 164 156 163 -136 0 -136 0
                -342 -346 -343 -346 -342 346 -342 346 -136 0 -137 0 156 -162z m286 -383 c67
                -24 201 -118 296 -208 l86 -81 -64 -58 c-98 -90 -217 -178 -283 -210 -139 -65
                -302 -8 -380 133 -26 47 -30 64 -30 134 0 63 5 90 23 126 67 137 224 209 352
                164z m1237 -19 c63 -32 115 -83 145 -145 18 -36 23 -63 23 -126 0 -70 -4 -87
                -30 -134 -53 -96 -161 -161 -268 -161 -90 0 -223 79 -395 238 l-64 58 86 81
                c91 86 227 183 292 208 57 22 148 14 211 -19z"
            ></path>
            <path
              d="M1065 220 l-69 -70 64 -65 c35 -36 69 -65 75 -65 6 0 40 29 75 65
                l65 65 -70 70 -70 70 -70 -70z"
            ></path>
          </g>
        </svg>
      </a>
    </div>
  </footer>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
  name: 'TheFooter',
  computed: {
    ...mapGetters({
      getSettings: 'app_settings/getSettings',
    }),
  },
}
</script>

<style lang="scss" scoped>
footer {
  background-color: $text-primary;
  .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: $text-secondary;
    a {
      font-size: 20px;
      font-style: normal;
      font-weight: 400;
      line-height: 145%;
      letter-spacing: -0.4px;
      color: $text-secondary;
      text-decoration: none;
    }
  }
}
</style>
