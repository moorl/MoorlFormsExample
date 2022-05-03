import MoorlFbExamplePlugin from './fb-example/fb-example.plugin';

const PluginManager = window.PluginManager;

/**
 * Every element type can be extended with a Storefront JavaScript plugin.
 * Just use "[data-moorl-fb-{name}]" as selector!
 */
PluginManager.register('MoorlFbExample', MoorlFbExamplePlugin, '[data-moorl-fb-example]');

if (module.hot) {
    module.hot.accept();
}
