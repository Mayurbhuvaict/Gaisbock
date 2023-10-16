const webpack = require('webpack');
const path    = require('path');

const { IS_STANDALONE_BUILD, src, resolveModulesPath } = require('./sobop.lib');

// USED IN BOTH SHOPWARE & DEPLOYMENT BUILD PROCESS
const packages = {
    '@NetiNextStoreLocator': path.resolve(__dirname, '..'),
    '@vue': resolveModulesPath('vue/dist/vue.common'),
    '@marker-clusterer': resolveModulesPath('@googlemaps/markerclusterer/dist/index.umd'),
};

// USED IN DEPLOYMENT BUILD PROCESS
const standaloneConfig = {
    mode: 'production',
    entry: path.resolve(__dirname, '../src/dev.js'),
    output: {
        path: path.resolve(__dirname, '../dist'),
        filename: 'store-locator.min.js'
    },
    resolve: {
        extensions: ['.js'],
        alias: {
            src,
            ...packages
        }
    },
    module: {
        rules: [
            {
                test: /\.m?js$/,
                exclude: /(node_modules|bower_components|dist)/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['@babel/preset-env'],
                        plugins: [
                            [
                                '@babel/plugin-proposal-class-properties'
                            ]
                        ]
                    }
                }
            }
        ]
    }
};

// USED IN SHOPWARE BUILD PROCESS
const shopwareConfig = () => {
    const isWebpackBuild = !(
        '' === process.env.CI_JOB_ID || undefined === process.env.CI_JOB_ID
    );

    return {
        resolve: {
            alias: packages
        },
        plugins: [
            new webpack.DefinePlugin({
                'process.env.NETI_WEBPACK_BUILD': JSON.stringify(isWebpackBuild ? 'true' : 'false')
            }),
        ]
    };
};

module.exports = IS_STANDALONE_BUILD ? standaloneConfig : shopwareConfig;