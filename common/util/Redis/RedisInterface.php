<?php

namespace app\common\util\Redis;

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
interface RedisInterface
{
}