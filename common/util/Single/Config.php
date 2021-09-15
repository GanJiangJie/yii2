<?php

namespace app\common\util\Single;

use app\common\traits\SingleTrait;

class Config
{
    use SingleTrait;

    /**
     * @var array $config
     */
    private $config = [];

    /**
     * @param string $key
     * @return string|array|null
     */
    public function get(string $key)
    {
        $key_array = explode('.', $key);
        $file_name = array_shift($key_array);
        if ($file_name == 'params') {
            if (empty($key_array)) return app()->params;
            if (is_array(app()->params)) return arraySeriesIndex(app()->params, $key_array);
            return null;
        }
        if (!isset($this->config[$file_name])) {
            $config_file = BASE_PATH . '/config/' . $file_name . '.php';
            if (!is_file($config_file)) return null;
            $this->config[$file_name] = include($config_file . '');
        }
        if (is_array($this->config[$file_name])) return arraySeriesIndex($this->config[$file_name], $key_array);
        return empty($key_array) ? $this->config[$file_name] : null;
    }
}