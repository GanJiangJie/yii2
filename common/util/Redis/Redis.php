<?php

namespace app\common\util\Redis;

use Closure;

/**
 * Redis命令方法,具体使用参考文档:http://redisdoc.com/
 *
 * @method set(string $key, string | int $value)
 * @method setex(string $key, int $seconds, string | int $value)
 * @method expire(string $key, int $seconds)
 * @method expireat(string $key, int $timestamp)
 * @method persist(string $key)
 * @method ttl(string $key)
 * @method type(string $key)
 * @method keys(string $key)
 * @method get(string $key)
 * @method exists(string $key)
 * @method del(string $key)
 * @method incr(string $key)
 * @method incrby(string $key, int $number)
 * @method decr(string $key)
 * @method decrby(string $key, int $number)
 * @method lpush(string $list, int | string | array $args)
 * @method rpush(string $list, int | string | array $args)
 * @method lpop(string $list)
 * @method rpop(string $list)
 * @method rpoplpush(string $fromList, string $toList)
 * @method lrem(string $list, $count, int | string $value)
 * @method sadd(string $set, int | string $value)
 * @method sdiff(string $set1, string $set2)
 * @method sdiffstore(string $set, string $set1, string $set2)
 * @method spop(string $set)
 * @method sismember(string $set, int | string $value)
 * @method srandmember(string $set, $count = 1)
 * @method srem(string $set, ...$value)
 * @method scard(string $set)
 * @method zcard(string $set)
 * @method zcount(string $set, $min, $max)
 * @method zrangebyscore(string $set, $min, $max)
 * @method zrem(string $set, ...$value)
 * @method multi()
 * @method exec()
 * @method discard()
 * @method watch(...$key)
 * @method unwatch()
 * @method eval(string $script, int $keyCount, ...$args)
 * @method evalsha(string $sha)
 *
 * @method executeCommand(string $command, array $params = [])
 */
class Redis
{
    //释放锁LUA脚本
    const SCRIPT_UNLOCK = <<<EOT
if ARGV[1] == redis.call('get', KEYS[1])
then
return redis.call('del', KEYS[1])
else
return 0
end
EOT;

    private static function redis($redis = 'redis')
    {
        return redis($redis);
    }

    /**
     * Redis事务
     * @param Closure $closure
     * @param string $redisDB
     */
    public static function transaction(Closure $closure, $redisDB = 'redis')
    {
        $redis = self::redis($redisDB);
        $redis->multi();//开启事务
        $result = call_user_func($closure, $redis);//执行命令
        if ($result === false) {
            $redis->discard();//事务回滚
        }
        $redis->exec();//事务提交
    }

    /**
     * 获取锁
     * @param $key
     * @param $value
     * @param $seconds
     * @param string $redisDB
     * @return bool
     */
    public static function lock($key, $value, $seconds, $redisDB = 'redis')
    {
        return (bool)self::redis($redisDB)->executeCommand('set', [$key, $value, 'ex', $seconds, 'nx']);
    }

    /**
     * 释放锁
     * @param $key
     * @param $value
     * @param string $redisDB
     * @return bool
     */
    public static function unlock($key, $value, $redisDB = 'redis')
    {
        return (bool)self::redis($redisDB)->eval(self::SCRIPT_UNLOCK, 1, $key, $value);
    }

    //LUA脚本
    private static function scriptLoad($script, $redisDB = 'redis')
    {
        return self::redis($redisDB)->executeCommand('script', ['load', $script]);
    }
}