const mix = require('laravel-mix');

mix.js('src/index.js', 'dist/formoj.js')
    .webpackConfig({
        output: {
            libraryTarget:'umd'
        }
    });