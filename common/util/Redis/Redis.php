<?php

namespace app\common\util\Redis;

class Redis
{
    //释放锁的LUA脚本
    const SCRIPT_UNLOCK = <<<EOT
if ARGV[1] == redis.call('get', KEYS[1])
then
return redis.call('del', KEYS[1])
else
return 0
end
EOT;
    //释放锁的LUA脚本的SHA1校验码
    const SCRIPT_UNLOCK_SHA = '3f47d27464a4bb5de6ff2e9f6cf589ea4a306d80';

    private static function redis($redis)
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
        //return (bool)self::redis($redisDB)->evalsha(self::SCRIPT_UNLOCK_SHA, 1, $key, $value);
        return (bool)self::redis($redisDB)->eval(self::SCRIPT_UNLOCK, 1, $key, $value);
    }

    /**
     * LUA脚本装载
     * @param $script
     * @param string $redisDB
     * @return mixed SHA1校验码
     */
    public static function scriptLoad($script, $redisDB = 'redis')
    {
        return self::redis($redisDB)->executeCommand('script', ['load', $script]);
    }

    /**
     * LUA脚本存在
     * @param $sha
     * @param string $redisDB
     * @return bool
     */
    public static function scriptExists($sha, $redisDB = 'redis')
    {
        return (bool)self::redis($redisDB)->executeCommand('script', ['exists', $sha]);
    }
}