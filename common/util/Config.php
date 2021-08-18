<?php

namespace app\common\util;

use app\common\traits\SingleTrait;
use Yii;

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
    public function get($key)
    {
        $key_array = explode('.', $key);
        $file_name = $key_array[0];
        unset($key_array[0]);
        if (empty($this->config) || !isset($this->config[$file_name])) {
            $config_file = Yii::$app->basePath . '/config/' . $file_name . '.php';
            if (!is_file($config_file)) return null;
            $this->config[$file_name] = (include($config_file . ''));
        }
        if (is_array($this->config[$file_name])) {
            return arraySeriesIndex($this->config[$file_name], $key_array);
        }
        return empty($key_array) ? $this->config[$file_name] : null;
    }
}