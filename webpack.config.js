const webpack = require("webpack");
let path = require('path');
const ExtractTextPlugin = require('extract-text-webpack-plugin');

var plugins = [
    new ExtractTextPlugin('/css/[name].css'),
    new webpack.ProvidePlugin({
        $: "jquery", jQuery: "jquery"
    }),
];

module.exports = {

    entry: [
        './assets/js/index.js',
        './assets/css/style.css'
    ],

    output: {
        path: path.resolve(__dirname, './public/dist'),
        filename: './js/[name].js',
        publicPath: '/'
    },

    module: {
        rules: [
            {
                test: /\.css$/,
                use: ExtractTextPlugin.extract('css-loader')
            },
            {
                test: /\.(png|jpg|svg|ttf|eot|woff|woff2)$/,
                use: 'file-loader'
            },
            {
                test: /\.(js|jsx)$/,
                exclude: /node_modules/,
                use: {
                    loader: "babel-loader"
                }
            }
        ]
    },
    plugins: plugins
};



