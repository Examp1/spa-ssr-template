export const getPageBySlug = async ({ $axios, redirect, i18n }, slug) => {
  try {
    const { data } = await $axios.$post('/api/page/get-by-slug', {
      lang: i18n.locale,
      slug
    });
    return data;
  } catch (error) {
    console.log('%cError:', 'color: red;', error);
    redirect('/404')
  }
};
