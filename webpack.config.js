const path = require('path');
const TerserPlugin = require('terser-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = (env) => ({
    mode: env?.production ? 'production' : 'development',
    watch: !!env?.watch,
    entry: {
        app: './src/main.js'
    },
    output: {
        path: path.resolve(__dirname, 'dist'),
        filename: './js/main.js',
        clean: true
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: '../dist/css/main.css'
        })
    ],
    resolve: {
        extensions: ['.js']
    },
    module: {
        rules: [
            {
                test: '/\.js$/',
                loader: 'babel-loader'
            },
            {
                test: /\.(sass|scss|css)$/,
                use: [MiniCssExtractPlugin.loader, 'css-loader', 'sass-loader']
            },
            {
                test: /\.jpg/,
                type: 'asset/resource'
            }
        ]
    },
    optimization: {
        minimize: env?.production === 'production',
        minimizer: [new TerserPlugin()]
    },
    devtool: env?.production === 'production' ? 'eval-cheap-source-map' : 'source-map',
    devServer: {
        port: 5001,
        open: true,
        hot: true,
    }
})