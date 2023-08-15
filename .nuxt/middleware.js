const middleware = {}

middleware['isSSR'] = require('..\\middleware\\isSSR.js')
middleware['isSSR'] = middleware['isSSR'].default || middleware['isSSR']

export default middleware
