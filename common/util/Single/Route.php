<?php

namespace app\common\util\Single;

use app\common\middleware\Middleware;
use app\common\traits\InstanceTrait;
use common\util\DataCheck\DataCheck;
use common\util\DataCheck\Validator;

class Route
{
    use InstanceTrait;

    /**
     * @var array $route_paths
     */
    private $filePaths;

    /**
     * @var array $methods
     */
    private $methodRoutes;

    /**
     * @var array $routeBefore
     */
    private $routeBefore;

    /**
     * @var array $routeAfter
     */
    private $routeAfter;

    /**
     * @var string $method
     */
    public $method;

    /**
     * @var string $route
     */
    public $route;

    /**
     * @var float $runtime API耗时：/ms
     */
    public $runtime;

    /**
     * 路由文件路径归纳
     * Route constructor.
     */
    private function __construct()
    {
        $this->filePaths = getDirFile(BASE_PATH . '/routes', true);
        $this->method = requestParams('method');
    }

    /**
     * @throws \yii\base\Exception
     */
    public function handle()
    {
        $start = microtime(true);
        self::paramsCheck();//参数校验
        self::method();//method获取route
        self::beforeHandle();//before处理
        response()->data(app()->runAction($this->route));//响应结果
        self::afterHandle();//after处理
        $this->runtime = round((microtime(true) - $start) * 1000, 2);//API耗时：/ms
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
    private function paramsCheck()
    {
        Validator::notEmpty(request()->params, ['app_id', 'method', 'sign_type', 'version', 'sign']);//验证必填参数

        DataCheck::signType(requestParams('sign_type'));//验证签名类型

        DataCheck::version(requestParams('version'));//验证版本

        DataCheck::checkSign(request()->params);//验证签名
    }

    /**
     * @throws \yii\base\Exception
     */
    private function method()
    {
        //获取方法对应路由
        foreach ($this->filePaths as $filePath) {
            self::reset();
            $methodRoutes = include($filePath . '');
            is_array($methodRoutes) or $methodRoutes = [];
            $this->methodRoutes = array_merge($this->methodRoutes, $methodRoutes);
            if (isset($this->methodRoutes[$this->method])) {
                $this->route = $this->methodRoutes[$this->method];
                return;
            }
        }
        tbe('', API_ERROR_CODE_INVALID_METHOD);
    }

    /**
     * @throws \yii\base\Exception
     */
    private function beforeHandle()
    {
        if (empty($this->routeBefore[$this->route])) return;
        foreach ((array)$this->routeBefore[$this->route] as $middleware) {
            self::middleHandle($middleware);
        }
    }

    /**
     * @throws \yii\base\Exception
     */
    private function afterHandle()
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
        @list($alias, $param) = explode(':', $middlewareParam);
        isset(Middleware::$middleware[$alias]) or tbe('Middleware \'' . $alias . '\' not register');
        /**
         * @var Middleware $class
         */
        $class = Middleware::$middleware[$alias];
        if (!method_exists($class, 'handle')) {
            tbe('Undefined method \'handle\' of class \'' . $class . '\'');
        }
        $class::handle($param . '');
    }

    private function reset()
    {
        $this->methodRoutes = [];
        $this->routeBefore = [];
        $this->routeAfter = [];
        $this->route = '';
    }
}