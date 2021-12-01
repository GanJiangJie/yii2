<?php

namespace app\common\traits;

use app\common\util\Redis\RedisK;
use app\common\util\Redis\RedisS;

trait ThrottleTrait
{
    /**
     * @param string $key
     * @param int $limit
     * @param int $second
     * @return bool
     * @throws \app\components\Exception
     */
    public static function throttle(string $key, int $limit, int $second): bool
    {
        $redis = config('params.throttle.driver');
        $count = redis(RedisS::class, 'Get', [$key], $redis);
        if ($count >= $limit) return false;
        redis(RedisS::class, 'InCr', [$key], $redis);
        if (!$count) redis(RedisK::class, 'Expire', [$key, $second], $redis);
        return true;
    }
}