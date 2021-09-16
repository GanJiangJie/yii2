<?php

namespace common\util\DataCheck;

use app\common\util\Redis\RedisS;
use yii\base\Exception;

class DataCheck
{
    /**
     * 校验版本号
     * @param string $version
     * @throws Exception
     */
    public static function version(string $version)
    {
        $version == config('params.open.version') or tbe('', API_ERROR_CODE_INVALID_VERSION);
    }

    /**
     * 校验签名类型
     * @param string $sign_type
     * @throws Exception
     */
    public static function signType(string $sign_type)
    {
        $sign_type == config('params.open.sign_type') or tbe('', API_ERROR_CODE_INVALID_SIGN_TYPE);
    }

    /**
     * 验签
     * @param array $params
     * @throws Exception
     */
    public static function checkSign(array $params)
    {
        $app_key = redis(RedisS::class, 'Get', [$params['app_id']]);
        empty($app_key) and tbe('', API_ERROR_CODE_INVALID_APP_ID);
        $sign = $params['sign'];
        unset($params['sign']);
        ksort($params);
        $string = urldecode(http_build_query($params)) . '&key=' . $app_key;
        hash_equals($sign, md5($string)) or tbe('', API_ERROR_CODE_INVALID_SIGN);
    }

    /**
     * 签名
     * @param array $params 业务参数
     * @param string $method 方法名称
     * @return array
     */
    public static function sign(array $params, string $method): array
    {
        $params['app_id'] = config('params.open.app_id');
        $params['sign_type'] = config('params.open.sign_type');
        $params['version'] = config('params.open.version');
        $params['method'] = $method;
        ksort($params);
        $string = urldecode(http_build_query($params)) . '&key=' . config('params.open.app_key');
        $params['sign'] = md5($string);
        return $params;
    }
}