var path = require('path');
var webpack = require('webpack');
var ExtractTextPlugin = require('extract-text-webpack-plugin');

module.exports = {
    entry: './app/Resources/js/app.js',
    output: {
        path: './web/js',
        filename: 'bundle.js'
    },
    devtool: 'source-map',
    module: {
        loaders: [
            {
                test: /.jsx?$/,
                loader: 'babel-loader',
                exclude: /node_modules/,
                query: {
                    presets: ['es2015', 'react']
                }
            },
            {
                test: /\.scss$/,
                loader: ExtractTextPlugin.extract('style-loader', 'css-loader?sourceMap!sass-loader?config=sassLoader'),
                fallbackLoader: 'style-loader!css-loader!sass-loader'
            },
            {
                test: /\.(png|jpg|jpeg|gif|svg|woff|woff2|ttf|eot)(\?.*$|$)/,
                loader: 'file'
            }
        ]
    },
    plugins: [
        new ExtractTextPlugin('bundle.css')
    ],
    sassLoader: {
        outputStyle: 'expanded',
        sourceMap: 'true',
        outFile: './web/css/bundle.css'
    }
};
