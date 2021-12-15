<?php

namespace app\common\util\Redis;

/**
 * redis sorted set 有序集合命令
 * Class RedisSSet
 * @package app\common\util\Redis
 */
class RedisSSet extends RedisBase
{
    /**
     * 向有序集合添加一个或多个成员，或者更新已有成员
     * @param $set
     * @param $params
     * @return mixed
     */
    public static function ZAdd($set, $params)
    {
        $set = parent::PREFIX . $set;
        array_unshift($params, $set);
        return app()->redis->executeCommand('ZADD', $params);
    }

    /**
     * 获取有序集合的成员数
     * @param $set
     * @return mixed
     */
    public static function ZCard($set)
    {
        $set = parent::PREFIX . $set;
        return app()->redis->zcard($set);
    }

    /**
     * 获取指定分数区间的成员数
     * @param $set
     * @param integer $min 分数下线
     * @param integer $max 分数上线
     * @return mixed
     */
    public static function ZCount($set, $min, $max)
    {
        $set = parent::PREFIX . $set;
        return app()->redis->zcount($set, $min, $max);
    }

    /**
     * Redis ZRangeByScore 返回有序集合中指定分数区间的成员列表。有序集成员按分数值递增(从小到大)次序排列。
     * 具有相同分数值的成员按字典序来排列(该属性是有序集提供的，不需要额外的计算)。
     * 默认情况下，区间的取值使用闭区间 (小于等于或大于等于)，你也可以通过给参数前增加 ( 符号来使用可选的开区间 (小于或大于)。
     * @param $set
     * @param $min
     * @param $max
     * @return mixed
     */
    public static function ZRangeByScore($set, $min, $max)
    {
        $set = parent::PREFIX . $set;
        return app()->redis->zrangebyscore($set, $min, $max);
    }

    /**
     * Redis ZRem 命令用于移除有序集中的一个或多个成员，不存在的成员将被忽略。
     * 当 key 存在但不是有序集类型时，返回一个错误。
     * @param $set
     * @param $member
     * @return mixed
     */
    public static function ZRem($set, $member)
    {
        $set = parent::PREFIX . $set;
        return app()->redis->zrem($set, $member);
    }
}