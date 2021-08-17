<?php

namespace app\common\util\Redis;

use Yii;

class RedisBase
{
    const PREFIX = '';

    public static $redis = 'redis';

    public static function executeCommand($command, $params)
    {
        $redis = self::$redis;
        $params = self::format_array($params);
        return Yii::$app->$redis->executeCommand($command, $params);
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