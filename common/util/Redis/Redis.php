<?php

namespace app\common\util\Redis;

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
     * @param \Closure $closure
     * @param string $redisDB
     */
    public static function transaction(\Closure $closure, $redisDB = 'redis')
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