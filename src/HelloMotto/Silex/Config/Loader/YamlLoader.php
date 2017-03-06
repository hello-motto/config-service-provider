<?php

namespace HelloMotto\Silex\Config\Loader;

use HelloMotto\Silex\Config\Exception\LoaderException;
use Symfony\Component\Yaml\Yaml;

class YamlLoader extends AbstractLoader
{
    public function __construct($configConstants = [])
    {
        if(!class_exists('Symfony\Component\Yaml\Yaml')) {
            throw new LoaderException(LoaderException::YAML_NOT_LOADED);
        }
        parent::__construct($configConstants);
    }

    protected function parseFile($file)
    {
        return Yaml::parse($file);
    }
}