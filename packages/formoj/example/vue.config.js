const path = require('path');

module.exports = {
    configureWebpack: {
        resolve: {
            alias: {
                'formoj': path.resolve(__dirname, '../src'),
            }
        }
    }
};