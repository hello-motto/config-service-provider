<?php

namespace HelloMotto\Silex\Config\Application;

trait ConfigTrait
{
    /**
     * @param mixed $domain
     * @return array|string
     */
    public function config($domain = null)
    {
        return array_key_exists($domain, $this['config']) ? $this['config'][$domain] : $this['config'];
    }

    /**
     * @param mixed $key
     * @return array|string
     */
    public function parameters($key = null)
    {
        return array_key_exists($key, $this['parameters']) ? $this['parameters'][$key] : $this['parameters'];
    }

    /**
     * @param string $fileName
     * @return array
     */
    public function loadFile($fileName)
    {
        return $this['config.loader']->load($fileName)->getData();
    }

    /**
     * @param array $filesName
     * @return array
     */
    public function loadManyFiles(array $filesName)
    {
        return $this['config.loader']->loadMany($filesName)->getData();
    }
}