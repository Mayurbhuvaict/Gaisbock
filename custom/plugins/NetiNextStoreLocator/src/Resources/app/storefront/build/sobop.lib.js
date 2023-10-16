/**
 * This file contains helper methods etc. to keep the webpack.config.js as clean as possible.
 * The contents should not be edited. Minor changes should be done in the repository.
 *
 * @link https://gitlab.netinventors.de/shopware6/storefront-build-process
 */

const path           = require('path');
const { existsSync } = require('fs');

const PLUGINS_PATH = path.resolve(__dirname, '../../../../../../');

const IS_STANDALONE_BUILD = process.env.NETI_STANDALONE_BUILD === '1';

const src = (
    () => {
        return [
            '../../../../../../../vendor/shopware/storefront/Resources/app/storefront/src/',
            '../../../../../../../vendor/shopware/platform/src/Storefront/Resources/app/storefront/src/',
        ].map(entry => path.resolve(process.env.PWD, entry)).find(entry => existsSync(entry));
    }
)();

/**
 * Resolves the actual path of the plugin. It tries it also with a lowerCase name in case the plugin was installed by
 * composer.
 *
 * @param {string} pluginName
 * @returns {string|*}
 */
const resolvePluginPath = (pluginName) => {
    return [
        pluginName,
        pluginName.toLowerCase()
    ].map(entry => path.resolve(PLUGINS_PATH, entry)).find(entry => existsSync(entry));
};

/**
 * @param {string} module
 * @returns {string|*}
 */
const resolveModulesPath = (module) => {
    return path.resolve(__dirname, '../node_modules', module);
};

/**
 * @param {string} pluginName
 * @returns {string|*}
 */
const getPluginPackages = (pluginName) => {
    const webpackConfigPath = path.resolve(
        resolvePluginPath(pluginName),
        'src/Resources/app/storefront/build/webpack.config.js'
    );

    let webpackConfig = require(webpackConfigPath);

    if (typeof webpackConfig === 'function') {
        webpackConfig = webpackConfig();
    }

    return webpackConfig.resolve.alias;
};

module.exports = {
    PLUGINS_PATH,
    IS_STANDALONE_BUILD,

    src,
    resolvePluginPath,
    resolveModulesPath,
    getPluginPackages
};