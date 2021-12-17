<?php

namespace app\common\util\Redis;

class RedisS extends RedisBase
{
    /**
     * 获取key的值
     * @param $key
     * @return mixed
     */
    public static function Get($key)
    {
        return self::redis()->get(parent::PREFIX . $key);
    }

    /**
     * 设置key值
     * @param $key
     * @param $value
     * @return mixed
     */
    public static function Set($key, $value)
    {
        return self::redis()->set(parent::PREFIX . $key, $value);
    }

    /**
     * 获取多个key的值
     * @param array $keys
     * @return array
     */
    public static function MGet($keys = array())
    {
        if (!empty(parent::PREFIX)) {
            $keys = array_map(function ($key) {
                return parent::PREFIX . $key;
            }, $keys);
        }
        $values = self::redis()->executeCommand('MGet', $keys);
        return array_combine($keys, $values);
    }

    /**
     * 设置多个key的值
     * @param array $params
     * @return mixed
     */
    public static function MSet($params = array())
    {
        return self::redis()->executeCommand('MSet', parent::formatArray($params));
    }

    /**
     * 设置key的值，并设置过期时间 以秒为单位
     * @param $key
     * @param $value
     * @param $seconds
     * @return mixed
     */
    public static function SetEx($key, $value, $seconds)
    {
        return self::redis()->setex(parent::PREFIX . $key, $seconds, $value);
    }

    /**
     * Redis InCr 命令将 key 中储存的数字值增一。
     * 如果 key 不存在，那么 key 的值会先被初始化为 0 ，然后再执行 INCR 操作。
     * 如果值包含错误的类型，或字符串类型的值不能表示为数字，那么返回一个错误。
     * 本操作的值限制在 64 位(bit)有符号数字表示之内。
     * @param $key
     * @return mixed
     */
    public static function InCr($key)
    {
        return self::redis()->incr(parent::PREFIX . $key);
    }

    /**
     * Redis InCrBy 命令将 key 中储存的数字加上指定的增量值。
     * 如果 key 不存在，那么 key 的值会先被初始化为 0 ，然后再执行 INCRBY 命令。
     * 如果值包含错误的类型，或字符串类型的值不能表示为数字，那么返回一个错误。
     * 本操作的值限制在 64 位(bit)有符号数字表示之内。
     * @param $key
     * @param $amount
     * @return mixed
     */
    public static function InCrBy($key, $amount)
    {
        return self::redis()->incrby(parent::PREFIX . $key, $amount);
    }

    /**
     * Redis DeCr 命令将 key 中储存的数字值减一。
     * 如果 key 不存在，那么 key 的值会先被初始化为 0 ，然后再执行 DECR 操作。
     * 如果值包含错误的类型，或字符串类型的值不能表示为数字，那么返回一个错误。
     * 本操作的值限制在 64 位(bit)有符号数字表示之内。
     * @param $key
     * @return mixed
     */
    public static function DeCr($key)
    {
        return self::redis()->decr(parent::PREFIX . $key);
    }

    /**
     * Redis DeCrBy 命令将 key 所储存的值减去指定的减量值。
     * 如果 key 不存在，那么 key 的值会先被初始化为 0 ，然后再执行 DECRBY 操作。
     * 如果值包含错误的类型，或字符串类型的值不能表示为数字，那么返回一个错误。
     * 本操作的值限制在 64 位(bit)有符号数字表示之内。
     * @param $key
     * @param $amount
     * @return mixed
     */
    public static function DeCrBy($key, $amount)
    {
        return self::redis()->decrby(parent::PREFIX . $key, $amount);
    }
}