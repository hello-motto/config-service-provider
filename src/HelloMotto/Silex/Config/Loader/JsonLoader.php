<?php

namespace HelloMotto\Silex\Config\Loader;

use HelloMotto\Silex\Config\Exception\LoaderException;

class JsonLoader extends AbstractLoader
{
    protected function parseFile($file)
    {
        $file = json_decode($file, true);

        if(json_last_error() !== 0) {
            throw new LoaderException(json_last_error());
        }

        return $file;
    }
}