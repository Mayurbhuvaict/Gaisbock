export default 'true' === process.env.NETI_WEBPACK_BUILD
    ? require('./prod')
    : require('./dev');