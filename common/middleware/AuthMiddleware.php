<?php

namespace app\common\middleware;

class AuthMiddleware extends Middleware
{
    /**
     * 验证登录
     * @param string $param
     * @throws \yii\base\Exception
     */
    public static function handle(string $param)
    {
        token()->check();
    }
}