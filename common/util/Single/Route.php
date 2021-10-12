<?php

namespace app\common\util\Single;

use app\common\traits\InstanceTrait;

class Route
{
    use InstanceTrait;

    /**
     * @var array $route_paths
     */
    private $routePaths;

    /**
     * Route constructor.
     */
    private function __construct()
    {
        //路由文件路径归纳
        $this->routePaths = getDirFile(BASE_PATH . '/routes', true);
    }

    /**
     * @param string $method
     * @return string
     * @throws \yii\base\Exception
     */
    public function method(string $method)
    {
        //获取方法对应路由
        foreach ($this->routePaths as $routePath) {
            $routes = (include($routePath . '')) ?: [];
            if (is_array($routes) && isset($routes[$method])) return $routes[$method];
        }
        tbe('', API_ERROR_CODE_INVALID_METHOD);
    }
}