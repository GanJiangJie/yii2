<?php

namespace app\common\middleware;

use app\common\middleware\model\AuthMiddleware;
use app\common\middleware\model\MerchantThrottleMiddleware;
use yii\base\Exception;

abstract class Middleware
{
    static $middleware = [
        'auth' => AuthMiddleware::class,
        'merThrottle' => MerchantThrottleMiddleware::class,
    ];

    /**
     * @param string $param
     * @throws Exception
     */
    abstract public static function handle(string $param);
}