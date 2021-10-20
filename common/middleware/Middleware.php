<?php

namespace app\common\middleware;

use yii\base\Exception;

abstract class Middleware
{
    static $middleware = [
        'auth' => AuthMiddleware::class,
    ];

    /**
     * @param string $param
     * @throws Exception
     */
    abstract public static function handle(string $param);
}