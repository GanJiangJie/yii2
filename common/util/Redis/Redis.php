<?php

namespace app\common\util\Redis;

class Redis
{
    //释放锁的LUA脚本
    private static $scriptUnlock = <<<EOD
if ARGV[1] == redis.call('get', KEYS[1])
then
return redis.call('del', KEYS[1])
else
return 0
end
EOD;
    //释放锁的LUA脚本的SHA1校验码
    private static $scriptUnlockSha = '3f47d27464a4bb5de6ff2e9f6cf589ea4a306d80';

    /**
     * @var RedisInterface
     */
    private static $redis;

    /**
     * Redis配置
     * @param string $redis
     */
    public static function config($redis = 'redis')
    {
        self::$redis = redis($redis);
    }

    /**
     * Redis事务
     * @param \Closure $closure
     */
    public static function transaction(\Closure $closure)
    {
        self::$redis->multi();//开启事务
        $result = call_user_func($closure, self::$redis);//执行命令
        if ($result === false) {
            self::$redis->discard();//事务回滚
        }
        self::$redis->exec();//事务提交
    }

    /**
     * 获取锁
     * @param $key
     * @param $value
     * @param $seconds
     * @return bool
     */
    public static function lock($key, $value, $seconds)
    {
        return (bool)self::$redis->executeCommand('set', [$key, $value, 'ex', $seconds, 'nx']);
    }

    /**
     * 自旋锁
     * @param $key
     * @param $value
     * @param $seconds
     * @param int $num
     * @param int $time
     * @param bool $type
     * @return bool
     */
    public static function spinLock($key, $value, $seconds, $num = 2, $time = 1, $type = false)
    {
        for ($i = 1; $i <= $num; $i++) {
            $lock = self::lock($key, $value, $seconds);
            if ($lock || $i == $num) {
                return $lock;
            }
            if ($type) {
                //微秒
                usleep($time);
            } else {
                //秒
                sleep($time);
            }
        }
        return false;
    }

    /**
     * 释放锁
     * @param $key
     * @param $value
     * @return bool
     */
    public static function unlock($key, $value)
    {
        //return (bool)self::$redis->evalsha(self::$scriptUnlockSha, 1, $key, $value);
        return (bool)self::$redis->eval(self::$scriptUnlock, 1, $key, $value);
    }

    /**
     * LUA脚本装载
     * @param $script
     * @return mixed SHA1校验码
     */
    private static function scriptLoad($script)
    {
        return self::$redis->executeCommand('script', ['load', $script]);
    }

    /**
     * LUA脚本存在
     * @param $sha
     * @return bool
     */
    private static function scriptExists($sha)
    {
        return (bool)self::$redis->executeCommand('script', ['exists', $sha]);
    }
}