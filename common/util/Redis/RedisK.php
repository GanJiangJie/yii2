<?php

namespace app\common\util\Redis;

/**
 * redis set 键命令
 * Class RedisK
 * @package app\common\util\Redis
 */
class RedisK extends RedisBase
{
    /**
     * 删除已存在的键
     * @param $key_name
     * @return mixed
     */
    public static function Del($key_name)
    {
        $key_name = parent::PREFIX . $key_name;
        return app()->redis->del($key_name);
    }

    /**
     * 检查给定 key 是否存在
     * @param $key_name
     * @return bool
     */
    public static function Exists($key_name)
    {
        $key_name = parent::PREFIX . $key_name;
        if (app()->redis->exists($key_name) == 1) {
            return true;
        }
        return false;
    }

    /**
     * 设置key的过期时间（秒）
     * @param $key
     * @param integer $time 单位：秒
     * @return mixed
     */
    public static function Expire($key, $time)
    {
        $key = parent::PREFIX . $key;
        return app()->redis->expire($key, $time);
    }

    /**
     * 设置key过期的时间戳
     * @param $key
     * @param integer $time_stamp 时间戳
     * @return mixed
     */
    public static function ExpireAt($key, $time_stamp)
    {
        $key = parent::PREFIX . $key;
        return app()->redis->expireat($key, $time_stamp);
    }

    /**
     * 获取符合给定模式的key 例 key* 获取以‘key’开头的所有key
     * @param $pattern
     * @return mixed
     */
    public static function Keys($pattern)
    {
        $pattern = parent::PREFIX . $pattern;
        return app()->redis->keys($pattern);
    }

    /**
     * 移除key的过期时间
     * @param $key_name
     * @return mixed
     */
    public static function Persist($key_name)
    {
        $key_name = parent::PREFIX . $key_name;
        return app()->redis->persist($key_name);
    }

    /**
     * 获取key的剩余过期时间 以秒为单位 key不存在返回-2,key未设置过期时间返回-1,否则，以秒为单位，返回 key 的剩余生存时间
     * @param $key_name
     * @return mixed
     */
    public static function Ttl($key_name)
    {
        $key_name = parent::PREFIX . $key_name;
        return app()->redis->ttl($key_name);
    }

    /**
     * 获取key的值得类型
     * none (key不存在) string (字符串) list (列表) set (集合) zset (有序集) hash (哈希表)
     * @param $key
     * @return mixed
     */
    public static function Type($key)
    {
        $key = parent::PREFIX . $key;
        return app()->redis->type($key);
    }
}