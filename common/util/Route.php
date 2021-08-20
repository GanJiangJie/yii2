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
        self::readFileOne($route_paths, $path_dir);
        //method方法对应路由
        foreach ($route_paths as $route_path) {
            $routes = (include($route_path . '')) ?: [];
            if (isset($routes[$method])) {
                return $routes[$method];
            }
        }
        throw new Exception($GLOBALS['__API_ERROR_CODE'][API_ERROR_CODE_INVALID_METHOD], API_ERROR_CODE_INVALID_METHOD);
    }

    /**
     * @param $route_paths
     * @param $path
     */
    private static function readFileOne(&$route_paths, $path)
    {
        $content = scandir($path);
        foreach ($content as $v) {
            if ($v == '.' || $v == '..') {
                continue;
            }
            if (is_file($path . '/' . $v)) {
                $route_paths[] = $path . '/' . $v;
                continue;
            }
            if (is_dir($path . '/' . $v)) {
                self::readFileTwo($route_paths, $path . '/' . $v);
            }
        }
    }

    /**
     * @param $route_paths
     * @param $path
     */
    private static function readFileTwo(&$route_paths, $path)
    {
        $content = scandir($path);
        foreach ($content as $v) {
            if ($v == '.' || $v == '..') {
                continue;
            }
            if (is_file($path . '/' . $v)) {
                $route_paths[] = $path . '/' . $v;
                continue;
            }
            if (is_dir($path . '/' . $v)) {
                self::readFileOne($route_paths, $path . '/' . $v);
            }
        }
    }
}