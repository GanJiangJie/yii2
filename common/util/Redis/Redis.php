<?php

namespace app\common\util\Redis;

/**
 * Class Redis
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
 * @method executeCommand(string $command, array $params)
 *
 * @package app\common\util\Redis
 */
interface Redis
{
}