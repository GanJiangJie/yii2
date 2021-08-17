<?php

namespace app\common\util\Redis;

use Yii;

/**
 * redis set 队列命令
 * Class RedisL
 * @package app\common\util\Redis
 */
class RedisL extends RedisBase
{
    /**
     * Redis Lpush 命令将一个或多个值插入到列表头部。
     * 如果 key 不存在，一个空列表会被创建并执行 LPUSH 操作。
     * 当 key 存在但不是列表类型时，返回一个错误。
     * @param $list
     * @param $value
     * @return mixed
     */
    public static function Lpush($list, $value)
    {
        $redis = self::$redis;
        $list = parent::PREFIX . $list;
        if (is_array($value)) {
            array_unshift($value, $list);
            return Yii::$app->$redis->executeCommand('Lpush', $value);
        }
        return Yii::$app->$redis->lpush($list, $value);
    }

    /**
     * Redis Rpush 命令用于将一个或多个值插入到列表的尾部(最右边)。
     * 如果列表不存在，一个空列表会被创建并执行 RPUSH 操作。
     * 当列表存在但不是列表类型时，返回一个错误。
     * @param $list
     * @param $value
     * @return mixed
     */
    public static function Rpush($list, $value)
    {
        $redis = self::$redis;
        $list = parent::PREFIX . $list;
        if (is_array($value)) {
            array_unshift($value, $list);
            return Yii::$app->$redis->executeCommand('Rpush', $value);
        }
        return Yii::$app->$redis->rpush($list, $value);
    }

    /**
     * 移除并返回列表的第一个元素。
     * @param $list
     * @return mixed
     */
    public static function Lpop($list)
    {
        $redis = self::$redis;
        $list = parent::PREFIX . $list;
        return Yii::$app->$redis->lpop($list);
    }

    /**
     * 移除并返回列表的最后一个元素
     * @param $list
     * @return mixed
     */
    public static function Rpop($list)
    {
        $redis = self::$redis;
        $list = parent::PREFIX . $list;
        return Yii::$app->$redis->rpop($list);
    }

    /**
     * 移除列表的最后一个元素，并将该元素添加到另一个列表并返回
     * @param $list
     * @param $to_list
     * @return mixed
     */
    public static function Rpoplpush($list, $to_list)
    {
        $redis = self::$redis;
        $list = parent::PREFIX . $list;
        $to_list = parent::PREFIX . $to_list;
        return Yii::$app->$redis->rpoplpush($list, $to_list);
    }

    /**
     * Redis Lrem 根据参数 COUNT 的值，移除列表中与参数 VALUE 相等的元素。COUNT 的值可以是以下几种：
     * count > 0 : 从表头开始向表尾搜索，移除与 VALUE 相等的元素，数量为 COUNT 。
     * count < 0 : 从表尾开始向表头搜索，移除与 VALUE 相等的元素，数量为 COUNT 的绝对值。
     * count = 0 : 移除表中所有与 VALUE 相等的值。
     * @param $list
     * @param $value
     * @return mixed
     */
    public static function Lrem($list, $value)
    {
        $redis = self::$redis;
        $list = parent::PREFIX . $list;
        return Yii::$app->$redis->lrem($list, 0, $value);
    }
}