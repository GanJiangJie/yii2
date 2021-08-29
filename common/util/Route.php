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
     * @param $method
     * @return string
     * @throws Exception
     */
    public static function method($method): string
    {
        //路由文件路径归纳
        $route_paths = getDirFile(BASE_PATH . '/routes', true);
        //获取方法对应路由
        foreach ($route_paths as $route_path) {
            $routes = (include($route_path . '')) ?: [];
            if (isset($routes[$method])) return $routes[$method];
        }
        throwBaseException($GLOBALS['__API_ERROR_CODE'][API_ERROR_CODE_INVALID_METHOD], API_ERROR_CODE_INVALID_METHOD);
    }
}