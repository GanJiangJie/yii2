<?php

namespace app\common\util\Redis;

/**
 * Class RedisTransaction
 * @package app\common\util\Redis
 */
class RedisTransaction extends RedisBase
{
    /**
     * Redis Multi 命令用于标记一个事务块的开始。
     * 事务块内的多条命令会按照先后顺序被放进一个队列当中，最后由 EXEC 命令原子性(atomic)地执行。
     * @return mixed
     */
    public static function Multi()
    {
        return app()->redis->multi();
    }

    /**
     * Redis Exec 命令用于执行所有事务块内的命令。
     * @return mixed
     */
    public static function Exec()
    {
        return app()->redis->exec();
    }

    /**
     * Redis Discard 命令用于取消事务，放弃执行事务块内的所有命令
     * @return mixed
     */
    public static function Discard()
    {
        return app()->redis->discard();
    }

    /**
     * Redis Unwatch 命令用于取消 WATCH 命令对所有 key 的监视。
     * @return mixed
     */
    public static function Unwatch()
    {
        return app()->redis->unwatch();
    }

    /**
     * Redis Watch 命令用于监视一个(或多个) key ，如果在事务执行之前这个(或这些) key 被其他命令所改动，那么事务将被打断
     * @param $key
     * @return mixed
     */
    public static function Watch($key)
    {
        $key = parent::PREFIX . $key;
        return app()->redis->watch($key);
    }
}