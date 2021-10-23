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
     */
    public static function throttle(string $key, int $limit, int $second): bool
    {
        $count = RedisS::Get($key);
        if ($count >= $limit) return false;
        RedisS::InCr($key);
        if (!$count) RedisK::Expire($key, $second);
        return true;
    }
}