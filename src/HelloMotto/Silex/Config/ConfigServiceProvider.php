<?php

namespace HelloMotto\Silex\Config;

use HelloMotto\Silex\Config\Loader\ConfigLoader;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ConfigServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['config.files'] = [];
        $app['config.constants'] = [];
        $app['config.closures'] = [];

        try {
            $app['config.loader'] = function() use ($app) {
                return new ConfigLoader($app['config.constants']);
            };

            $app['config'] = function() use ($app) {
                $loader = $app['config.loader'];
                $loader->loadMany($app['config.files'])
                    ->addData($app['config.closures']);

                return $loader->getData();
            };

            $app['parameters'] = function() use ($app) {
                $parameters = [];
                if(array_key_exists('parameters', $app['config'])) {
                    $parameters = $app['config']['parameters'];
                }

                return $parameters;
            };
        } catch(\Exception $e) {
            $e->getMessage();
        }
   }
}
