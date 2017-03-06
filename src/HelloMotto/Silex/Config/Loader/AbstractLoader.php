<?php

namespace HelloMotto\Silex\Config\Loader;

use HelloMotto\Silex\Config\Exception\LoaderException;

abstract class AbstractLoader
{
    /**
     * @var array
     */
    protected $configConstants = [];

    /**
     * Locator variable is useful to import other files from JSON and YAML files
     *
     * @var string
     */
    protected $locator;

    /**
     * AbstractLoader constructor.
     * @param string $fileName
     * @return array
     */
    public function __construct(array $configConstants = [])
    {
        $this->configConstants = $configConstants;
    }

    /**
     * @param $fileName
     * @return array
     */
    public function load($fileName)
    {
        $this->locator = str_replace(basename($fileName), '', $fileName);

        return $this->importFile($fileName);
    }

    /**
     * @return array
     */
    public function getConfigConstants()
    {
        return $this->configConstants;
    }

    /**
     * @return array
     */
    public function addConfigConstant($key, $value)
    {
        if(!array_key_exists($key, $this->getConfigConstants())) {
            $this->configConstants[$key] = $value;
        }
        return $this;
    }

    /**
     * @param string $fileName
     * @return array
     */
    protected function importFile($fileName)
    {
        if(is_readable($fileName)) {
            $file = $this->replaceConstants($fileName);
            if(is_array($file)) {
                $data = $this->parseFile($file);
            } elseif(trim($file) !== '') {
                $data = $this->parseArray($this->parseFile($file));
            }
        } else {
            throw new LoaderException(LoaderException::NOT_READABLE_FILE, $fileName);
        }
        return isset($data) ? $data : [];
    }

    /**
     * @param array $configArray
     * @return array
     */
    protected function parseArray(array $configArray)
    {
        foreach($configArray as $key => $value) {
            if($key === 'imports' && is_array($value)) {
                foreach($value as $import) {
                    $subConfigArray = $this->parseArray($this->importFile($this->locator.$import['resource']));
                    $configArray = array_replace_recursive($configArray, $subConfigArray);
                }
                unset($configArray[$key]);
            }
        }
        return $configArray;
    }

    /**
     * Replace the constants before parsing the file, only for JSON ans YAML files
     *
     * @param string $fileName
     * @return string
     */
    protected function replaceConstants($fileName)
    {
        $constants = $this->getConfigConstants();
        return strtr(file_get_contents($fileName), $constants);
    }

    /**
     * @param string $file
     * @return array
     */
    abstract protected function parseFile($file);
}