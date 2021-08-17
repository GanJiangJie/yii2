<?php

namespace app\common\util\Redis;

use Yii;

/**
 * redis set 集合命令
 * Class RedisSet
 * @package app\common\util\Redis
 */
class RedisSet extends RedisBase
{

    public $Set = '';

    public function __construct($set_name)
    {
        $this->Set = $set_name;
    }

    //向集合添加一个或多个成员
    public function Sadd($value)
    {
        $redis = self::$redis;
        $set = parent::PREFIX . $this->Set;
        if (is_array($value)) {
            array_unshift($value, $set);
            return Yii::$app->$redis->executeCommand('Sadd', $value);
        }
        return Yii::$app->$redis->sadd($set, $value);
    }

    //Redis Sdiff 命令返回给定集合之间的差集。不存在的集合 key 将视为空集
    public static function Sdiff($set1, $set2)
    {
        $redis = self::$redis;
        $set1 = parent::PREFIX . $set1;
        $set2 = parent::PREFIX . $set2;
        return Yii::$app->$redis->sdiff($set1, $set2);
    }

    //Redis Sdiffstore 命令将给定集合之间的差集存储在指定的集合中。如果指定的集合 key 已存在，则会被覆盖
    public static function Sdiffstore($set, $set1, $set2)
    {
        $redis = self::$redis;
        $set = parent::PREFIX . $set;
        $set1 = parent::PREFIX . $set1;
        $set2 = parent::PREFIX . $set2;
        return Yii::$app->$redis->sdiffstore($set, $set1, $set2);
    }

    //Redis Spop 命令用于移除并返回集合中的一个随机元素
    public function Spop()
    {
        $redis = self::$redis;
        $set = parent::PREFIX . $this->Set;
        return Yii::$app->$redis->spop($set);
    }

    //Redis Sismember 命令判断成员元素是否是集合的成员
    public function Sismember($value)
    {
        $redis = self::$redis;
        $set = parent::PREFIX . $this->Set;
        return Yii::$app->$redis->sismember($set, $value);
    }

    //Redis Srandmember 命令用于返回集合中的一个随机元素
    public function Srandmember($count = 0)
    {
        $redis = self::$redis;
        $set = parent::PREFIX . $this->Set;
        if ($count == 0) {
            return Yii::$app->$redis->srandmember($set);
        }
        return Yii::$app->$redis->srandmember($set, $count);
    }

    //Redis Srem 命令用于移除集合中的一个或多个成员元素，不存在的成员元素会被忽略
    public function Srem($value)
    {
        $redis = self::$redis;
        $set = parent::PREFIX . $this->Set;
        return Yii::$app->$redis->srem($set, $value);
    }

    //Redis Scard 命令返回集合中元素的数量
    public function Scard()
    {
        $redis = self::$redis;
        $set = parent::PREFIX . $this->Set;
        return Yii::$app->$redis->scard($set);
    }
}