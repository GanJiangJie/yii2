<?php

namespace app\common\util\Redis;

class RedisBase
{
    const PREFIX = '';

    /**
     * @param string $command
     * @param array $params
     * @return mixed
     */
    public static function executeCommand(string $command, array $params)
    {
        return self::redis()->executeCommand($command, self::formatArray($params));
    }

    /**
     * @return Redis
     */
    protected static function redis()
    {
        return redis();
    }

    /**
     * 格式化数组 ['a'=>1,'b'=>2] 转化 ['a',1,'b',2]
     * @param array $params
     * @return array
     */
    protected static function formatArray(array $params)
    {
        if (count(array_filter(array_keys($params), 'is_string')) > 0) {
            //关联数组
            $value = [];
            foreach ($params as $k => $v) {
                $value[] = self::PREFIX . $k;
                $value[] = $v;
            }
            return $value;
        }
        return $params;
    }
}