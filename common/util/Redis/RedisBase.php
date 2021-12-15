<?php

namespace app\common\util\Redis;

class RedisBase
{
    const PREFIX = '';

    public static function executeCommand($command, $params)
    {
        $params = self::format_array($params);
        return app()->redis->executeCommand($command, $params);
    }

    /**
     * 格式化数组
     * @param $arr
     * @return array
     */
    public static function format_array($arr)
    {
        $value = [];
        foreach ($arr as $k => $v) {
            $value[] = self::PREFIX . $k;
            $value[] = $v;
        }
        return $value;
    }
}