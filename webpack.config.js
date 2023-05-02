const path = require("path");
const TerserPlugin = require("terser-webpack-plugin");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const CssMinimizerPlugin = require("css-minimizer-webpack-plugin");

module.exports = (env) => ({
    mode: env?.production ? "production" : "development",
    watch: !!env?.watch,
    entry: {
        app: "./src/main.js"
    },
    output: {
        path: path.resolve(__dirname, "dist"),
        filename: "./js/main.js",
        clean: true
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: "../dist/css/main.css"
        })
    ],
    resolve: {
        extensions: [ ".js" ]
    },
    module: {
        rules: [
            {
                test: "/\.js$/",
                loader: "babel-loader"
            },
            {
                test: /\.(sass|scss|css)$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    {
                        loader: "css-loader",
                        options: {
                            url: false,
                        }
                    },
                    "sass-loader"
                ]
            }
        ]
    },
    optimization: {
        minimize: true,
        minimizer: [
            new TerserPlugin({
                extractComments: !env?.production,
                terserOptions: {
                    format: {
                        comments: !env?.production
                    },
                },
            }),
            new CssMinimizerPlugin()
        ]
    },
    ...(!env?.production) && { devtool: "source-map" },
    devServer: {
        port: 5001,
        open: true,
        hot: true,
    }
});
