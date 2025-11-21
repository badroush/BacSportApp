// webpack.config.dev-server.js
const config = require('./webpack.config.js');

config.devServer = {
    port: 8080,
    host: '127.0.0.1',
    hot: true,
    liveReload: true,
    headers: {
        'Access-Control-Allow-Origin': '*',
    },
    static: {
        directory: './public',
    },
    allowedHosts: 'all',
};

module.exports = config;
