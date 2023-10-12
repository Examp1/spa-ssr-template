export const getProductBySlug = async ({
    $axios,
    redirect,
    i18n,
    params,
    query,
}) => {
    try {
        const options = {
            lang: i18n.locale,
            slug: params.product,
            prevw: query.prevw,
        }
        if (query.filter) {
            options.filters = query.filter
        }

        const { data } = await $axios.$post('/api/product/get-by-slug', options)
        return data
    } catch (error) {
        console.log('%cError:', 'color: red;', error)

        redirect('/404')
    }
}
