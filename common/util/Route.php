<?php

namespace app\common\util;

use yii\base\Exception;

/**
 * 获取请求参数method对应访问路由
 * Class Route
 * @package app\common\util
 */
class Route
{
    /**
     * @param string $method 接口方法名称
     * @return bool|string
     * @throws Exception
     */
    public static function method($method)
    {
        //route目录绝对路径
        $path_dir = BASE_PATH . '/routes';
        //路由文件路径
        $route_paths = [];
        //路由文件路径归纳
        FolderFile::readFileOne($route_paths, $path_dir, true);
        //method方法对应路由
        foreach ($route_paths as $route_path) {
            $routes = (include($route_path . '')) ?: [];
            if (isset($routes[$method])) {
                return $routes[$method];
            }
        }
        throw new Exception($GLOBALS['__API_ERROR_CODE'][API_ERROR_CODE_INVALID_METHOD], API_ERROR_CODE_INVALID_METHOD);
    }
}