<?php

namespace app\common\middleware;

use yii\base\Exception;

abstract class Middleware
{
    static $middleware = [
        'auth' => 'app\common\middleware\model\AuthMiddleware',
        'mThrottle' => 'app\common\middleware\model\MerchantThrottleMiddleware',
        'uThrottle' => 'app\common\middleware\model\MemberThrottleMiddleware',
    ];

    /**
     * @param string $param
     * @throws Exception
     */
    abstract public static function handle(string $param);
}