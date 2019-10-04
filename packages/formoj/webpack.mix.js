const mix = require('laravel-mix');

mix.js('src/index.js', 'dist/formoj.js')
    .webpackConfig({
        output: {
            libraryTarget:'umd'
        },
        externals: [
            'axios',
            'core-js',
        ],
        module: {
            rules: [
                {
                    test: /\.js$/,
                    include: [
                        path.resolve(__dirname, 'node_modules/vue2-dropzone'),
                    ],
                    use: [
                        {
                            loader: 'babel-loader',
                            options: Config.babel()
                        }
                    ]
                }
            ]
        }
    });