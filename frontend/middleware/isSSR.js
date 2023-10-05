export default (context) => {
    const isSSR = process.server

    context.store.commit('checkMode/setMode', isSSR ? 'ssr' : 'spa')
}
