export default async (context) => {
  const $axios = context.$axios
  const store = context.store
  const i18n = context.i18n
  // const cookies = context.$cookies
  // fetch setting
  if (store.getters['app_settings/getSettings'] === null) {
      const res = await $axios.$post('/api/settings/all', {
          lang: i18n.locale,
          // lang: 'uk',
      })
      store.dispatch('app_settings/setSettings', res.data)
  }
  // fetch menu

  const response = await $axios.$post('/api/menu/get-by-ids', {
      lang: i18n.locale,
      ids: [1, 2, 43],
  })
  store.dispatch('menu/setMenuData', response.data)

  // if (!store.getters["personalAccount/getAccessToken"]) {
  //     store.dispatch("personalAccount/setAccessToken", cookies.get('accessToken'))
  // }
  // if (!store.getters["personalAccount/getUser"]) {
  //     store.dispatch("personalAccount/setUser", cookies.get('user'))
  // }
}
