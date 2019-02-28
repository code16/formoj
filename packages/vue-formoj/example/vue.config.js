const path = require('path');

module.exports = {
    configureWebpack: {
        resolve: {
            modules: [
                path.resolve(__dirname, '../..'),
                'node_modules',
            ],
        }
    }
};