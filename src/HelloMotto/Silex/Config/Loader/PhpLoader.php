<?php

namespace HelloMotto\Silex\Config\Loader;

class PhpLoader extends AbstractLoader
{
    /**
     * This method is useless for PHP files
     *
     * @param array $file
     * @return array
     */
    protected function parseFile($file)
    {
        return $file;
    }

    /**
     * This method is useless for PHP files
     *
     * @param string $fileName
     * @return string
     */
    protected function replaceConstants($fileName)
    {
        return require $fileName;
    }
}