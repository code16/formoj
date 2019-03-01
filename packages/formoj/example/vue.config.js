const path = require('path');
const server = require('./server');

module.exports = {
    configureWebpack: {
        resolve: {
            modules: [
                path.resolve(__dirname, '../..'),
                'node_modules',
            ],
        }
    },
    devServer: {
        before(app) {
            server(app);
        },
    }
};