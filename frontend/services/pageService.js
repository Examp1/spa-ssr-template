export const getPageBySlug = async ({
    $axios,
    redirect,
    i18n,
    params,
    query,
}) => {
    try {
        const options = {
            lang: i18n.locale,
            slug: params.page ? params.page : '/',
            prevw: query.prevw,
            page: query.page,
        }
        if (query.filter) {
            options.filters = query.filter
        }

        const { data } = await $axios.$post('/api/page/get-by-slug', options)
        return data
    } catch (error) {
        console.log('%cError:', 'color: red;', error)

        redirect('/404')
    }
}
