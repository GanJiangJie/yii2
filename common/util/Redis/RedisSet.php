<?php

namespace app\common\util\Redis;

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
    public function SAdd($value)
    {
        $set = parent::PREFIX . $this->Set;
        if (is_array($value)) {
            array_unshift($value, $set);
            return app()->redis->executeCommand('Sadd', $value);
        }
        return app()->redis->sadd($set, $value);
    }

    //Redis SDiff 命令返回给定集合之间的差集。不存在的集合 key 将视为空集
    public static function SDiff($set1, $set2)
    {
        $set1 = parent::PREFIX . $set1;
        $set2 = parent::PREFIX . $set2;
        return app()->redis->sdiff($set1, $set2);
    }

    //Redis SDiffStore 命令将给定集合之间的差集存储在指定的集合中。如果指定的集合 key 已存在，则会被覆盖
    public static function SDiffStore($set, $set1, $set2)
    {
        $set = parent::PREFIX . $set;
        $set1 = parent::PREFIX . $set1;
        $set2 = parent::PREFIX . $set2;
        return app()->redis->sdiffstore($set, $set1, $set2);
    }

    //Redis SPop 命令用于移除并返回集合中的一个随机元素
    public function SPop()
    {
        $set = parent::PREFIX . $this->Set;
        return app()->redis->spop($set);
    }

    //Redis SisMember 命令判断成员元素是否是集合的成员
    public function SisMember($value)
    {
        $set = parent::PREFIX . $this->Set;
        return app()->redis->sismember($set, $value);
    }

    //Redis SRandMember 命令用于返回集合中的一个随机元素
    public function SRandMember($count = 0)
    {
        $set = parent::PREFIX . $this->Set;
        if ($count == 0) {
            return app()->redis->srandmember($set);
        }
        return app()->redis->srandmember($set, $count);
    }

    //Redis SRem 命令用于移除集合中的一个或多个成员元素，不存在的成员元素会被忽略
    public function SRem($value)
    {
        $set = parent::PREFIX . $this->Set;
        return app()->redis->srem($set, $value);
    }

    //Redis SCard 命令返回集合中元素的数量
    public function SCard()
    {
        $set = parent::PREFIX . $this->Set;
        return app()->redis->scard($set);
    }
}