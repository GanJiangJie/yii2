<?php

namespace app\common\traits;

trait ThrottleTrait
{
    /**
     * @param string $key
     * @param int $limit
     * @param int $seconds
     * @return bool
     */
    public static function throttle(string $key, int $limit, int $seconds): bool
    {
        $redis = redis(config('params.throttle.driver'));
        $count = $redis->get($key);
        if ($count >= $limit) {
            return false;
        }
        $redis->incr($key);
        if (!$count) {
            $redis->expire($key, $seconds);
        }
        return true;
    }
}