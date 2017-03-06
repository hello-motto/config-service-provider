<?php

namespace HelloMotto\Silex\Config\Loader;

use HelloMotto\Silex\Config\Exception\LoaderException;

class ConfigLoader
{
    /**
     * @var array
     */
    protected $configConstants = [];

    /**
     * @var array
     */
    protected $data = [];

    /**
     * ConfigLoader constructor.
     * @param array $configConstants
     */
    public function __construct(array $configConstants = [])
    {
        foreach($configConstants as $key => $value) {
            $this->configConstants[$key] = addslashes($value);
        }
    }

    /**
     * Parse a file and add it to the data
     *
     * @param $fileName
     * @return array
     */
    public function load($fileName)
    {
        $loader = $this->guessLoader($fileName);
        $this->addData($loader->load($fileName));

        return $this;
    }

    /**
     * Parse several files and add them to the data
     *
     * @param array $filesName
     * @return $this|ConfigLoader
     */
    public function loadMany(array $filesName)
    {
        foreach($filesName as $fileName) {
            $this->load($fileName);
        }

        return $this;
    }

    /**
     * @param array $newData
     * @return $this|ConfigLoader
     */
    public function addData(array $newData)
    {
        $this->data = array_merge_recursive($this->data, $newData);

        return $this;
    }

    /**
     * Analyze the data to check if there are new constants to add
     * Then parse them and replace all the constants and return the data
     * @return array
     */
    public function getData()
    {
        $this->checkConstants();

        return self::replaceConstants($this->data, $this->configConstants);
    }

    /**
     * @param array $data
     * @param array $constants
     * @return array
     */
    public static function replaceConstants(array $data, array $constants)
    {
        foreach($data as $key => $value) {
            if(is_array($value)) {
                $data[$key] = self::replaceConstants($value, $constants);
            } elseif(is_string($value)) {
                $data[$key] = $value === '~' ? null :
                    (strpos($value, '%') !== false ? strtr($value, $constants) : $value);
            }
        }
        return $data;
    }

    /**
     * Analyze the file and use the loader for the file type
     *
     * @param $filename
     * @return JsonLoader|PhpLoader|YamlLoader
     */
    protected function guessLoader($filename)
    {
        if(is_file($filename)) {
            $mimeType = mime_content_type($filename);
        } else {
            throw new LoaderException(LoaderException::FILE_NOT_FOUND, $filename);
        }

        if(stripos('php', $mimeType) || stripos($filename, '.php')) {
            return new PhpLoader();
        } elseif(stripos('json', $mimeType) || stripos($filename, '.json')) {
            return new JsonLoader($this->configConstants);
        } elseif(stripos($filename, '.yml')) {
            return new YamlLoader($this->configConstants);
        } else {
            throw new LoaderException(LoaderException::NO_LOADER_FOUND, $filename);
        }
    }

    /**
     * @param array|null $data
     * @return $this|ConfigLoader
     */
    protected function checkConstants(array $data = null)
    {
        if($data === null) {
            $data = $this->data;
        }
        foreach($data as $key => $value) {
            if(is_array($value)) {
                $this->checkConstants($value);
            } elseif(!is_object($value) && strpos($key, '%') === 0 && !array_key_exists($key, $this->configConstants)) {
                $this->configConstants[$key] = $value;
            }
        }
        return $this;
    }
}