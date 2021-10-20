<?php

namespace app\common\util\Single;

use app\common\middleware\Middleware;
use app\common\traits\InstanceTrait;

class Route
{
    use InstanceTrait;

    /**
     * @var array $route_paths
     */
    private $filePaths = [];

    /**
     * @var array $methods
     */
    private $methodRoutes = [];

    /**
     * @var array $routeBefore
     */
    private $routeBefore = [];

    /**
     * @var array $routeAfter
     */
    private $routeAfter = [];

    /**
     * @var string $route
     */
    public $route = '';

    /**
     * 路由文件路径归纳
     * Route constructor.
     */
    private function __construct()
    {
        $this->filePaths = getDirFile(BASE_PATH . '/routes', true);
    }

    /**
     * @param string $method
     * @throws \yii\base\Exception
     */
    public function method(string $method)
    {
        //获取方法对应路由
        foreach ($this->filePaths as $filePath) {
            self::clear();
            $methodRoutes = (include($filePath . '')) ?: [];
            $this->methodRoutes = array_merge($this->methodRoutes, $methodRoutes);
            if (isset($this->methodRoutes[$method])) {
                $this->route = $this->methodRoutes[$method];
                return;
            }
        }
        tbe('', API_ERROR_CODE_INVALID_METHOD);
    }

    /**
     * @param array $ms
     * @param array $methodRoutes
     * @return $this
     */
    public function before(array $ms, array $methodRoutes)
    {
        $this->methodRoutes = array_merge($this->methodRoutes, $methodRoutes);
        foreach ($methodRoutes as $route) {
            $this->routeBefore[$route] = $ms;
        }
        return $this;
    }

    /**
     * @param array $ms
     * @param array $methodRoutes
     * @return $this
     */
    public function after(array $ms, array $methodRoutes)
    {
        $this->methodRoutes = array_merge($this->methodRoutes, $methodRoutes);
        foreach ($methodRoutes as $route) {
            $this->routeAfter[$route] = $ms;
        }
        return $this;
    }

    /**
     * @throws \yii\base\Exception
     */
    public function beforeHandle()
    {
        if (empty($this->routeBefore[$this->route])) return;
        foreach ((array)$this->routeBefore[$this->route] as $middleware) {
            self::middleHandle($middleware);
        }
    }

    /**
     * @throws \yii\base\Exception
     */
    public function afterHandle()
    {
        if (empty($this->routeAfter[$this->route])) return;
        foreach ((array)$this->routeAfter[$this->route] as $middleware) {
            self::middleHandle($middleware);
        }
    }

    /**
     * @param string $middlewareParam
     * @throws \yii\base\Exception
     */
    private function middleHandle(string $middlewareParam)
    {
        @list($middleware, $param) = explode(':', $middlewareParam);
        isset(Middleware::$middleware[$middleware]) or tbe('Middleware \'' . $middleware . '\' not register');
        /**
         * @var Middleware $middlewareClass
         */
        $middlewareClass = Middleware::$middleware[$middleware];
        if (!method_exists($middlewareClass, 'handle')) {
            tbe('Undefined method \'handle\' of class \'' . $middlewareClass . '\'');
        }
        $middlewareClass::handle($param ?: '');
    }

    private function clear()
    {
        $this->methodRoutes = [];
        $this->routeBefore = [];
        $this->routeAfter = [];
        $this->route = '';
    }
}