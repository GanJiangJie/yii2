<?php

namespace app\common\util\Redis;

use Yii;

class RedisS extends RedisBase
{
    /**
     * 获取key的值
     * @param $key
     * @return mixed
     */
    public static function Get($key)
    {
        $redis = self::$redis;
        $key = parent::PREFIX . $key;
        return Yii::$app->$redis->get($key);
    }

    /**
     * 设置key值
     * @param $key
     * @param $value
     * @return mixed
     */
    public static function Set($key, $value)
    {
        $redis = self::$redis;
        $key = parent::PREFIX . $key;
        return Yii::$app->$redis->set($key, $value);
    }

    /**
     * 获取多个key的值
     * @param array $keys
     * @return array
     */
    public static function MGet($keys = array())
    {
        $redis = self::$redis;
        foreach ($keys as $k => &$v) {
            $v = parent::PREFIX . $v;
        }
        $values = Yii::$app->$redis->executeCommand('MGet', $keys);
        $result = array();
        foreach ($values as $k => $v) {
            if (!empty($v)) {
                $result[$keys[$k]] = $v;
            } else {
                $result[$keys[$k]] = '';
            }
        }
        return $result;
    }

    /**
     * 设置多个key的值
     * @param array $params
     * @return mixed
     */
    public static function MSet($params = array())
    {
        $redis = self::$redis;
        $params = parent::format_array($params);
        return Yii::$app->$redis->executeCommand('MSet', $params);
    }

    /**
     * 设置key的值，并设置过期时间 以秒为单位
     * @param $key
     * @param $value
     * @param $time
     * @return mixed
     */
    public static function SetEx($key, $value, $time)
    {
        $redis = self::$redis;
        $key = parent::PREFIX . $key;
        return Yii::$app->$redis->setex($key, $time, $value);
    }

    /**
     * Redis Incr 命令将 key 中储存的数字值增一。
     * 如果 key 不存在，那么 key 的值会先被初始化为 0 ，然后再执行 INCR 操作。
     * 如果值包含错误的类型，或字符串类型的值不能表示为数字，那么返回一个错误。
     * 本操作的值限制在 64 位(bit)有符号数字表示之内。
     * @param $key
     * @return mixed
     */
    public static function InCr($key)
    {
        $redis = self::$redis;
        $key = parent::PREFIX . $key;
        return Yii::$app->$redis->incr($key);
    }

    /**
     * Redis Incrby 命令将 key 中储存的数字加上指定的增量值。
     * 如果 key 不存在，那么 key 的值会先被初始化为 0 ，然后再执行 INCRBY 命令。
     * 如果值包含错误的类型，或字符串类型的值不能表示为数字，那么返回一个错误。
     * 本操作的值限制在 64 位(bit)有符号数字表示之内。
     * @param $key
     * @param $amount
     * @return mixed
     */
    public static function InCrBy($key, $amount)
    {
        $redis = self::$redis;
        $key = parent::PREFIX . $key;
        return Yii::$app->$redis->incrby($key, $amount);
    }

    /**
     * Redis Decr 命令将 key 中储存的数字值减一。
     * 如果 key 不存在，那么 key 的值会先被初始化为 0 ，然后再执行 DECR 操作。
     * 如果值包含错误的类型，或字符串类型的值不能表示为数字，那么返回一个错误。
     * 本操作的值限制在 64 位(bit)有符号数字表示之内。
     * @param $key
     * @return mixed
     */
    public static function DeCr($key)
    {
        $redis = self::$redis;
        $key = parent::PREFIX . $key;
        return Yii::$app->$redis->decr($key);
    }

    /**
     * Redis Decrby 命令将 key 所储存的值减去指定的减量值。
     * 如果 key 不存在，那么 key 的值会先被初始化为 0 ，然后再执行 DECRBY 操作。
     * 如果值包含错误的类型，或字符串类型的值不能表示为数字，那么返回一个错误。
     * 本操作的值限制在 64 位(bit)有符号数字表示之内。
     * @param $key
     * @param $amount
     * @return mixed
     */
    public static function DeCrBy($key, $amount)
    {
        $redis = self::$redis;
        $key = parent::PREFIX . $key;
        return Yii::$app->$redis->decrby($key, $amount);
    }
}