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
    public static function checkVersion($version)
    {
        $version == params('open.version') or
        throwBaseException($GLOBALS['__API_ERROR_CODE'][API_ERROR_CODE_INVALID_VERSION], API_ERROR_CODE_INVALID_VERSION);
    }

    /**
     * 校验签名类型
     * @param string $sign_type
     * @throws Exception
     */
    public static function checkSignType($sign_type)
    {
        $sign_type == params('open.sign_type') or
        throwBaseException($GLOBALS['__API_ERROR_CODE'][API_ERROR_CODE_INVALID_SIGN_TYPE], API_ERROR_CODE_INVALID_SIGN_TYPE);
    }

    /**
     * 验签
     * @param array $params
     * @throws Exception
     */
    public static function checkSign($params)
    {
        $app_key = redis(RedisS::class, 'Get', [$params['app_id']]);
        empty($app_key) and
        throwBaseException($GLOBALS['__API_ERROR_CODE'][API_ERROR_CODE_INVALID_APP_ID], API_ERROR_CODE_INVALID_APP_ID);
        $sign = $params['sign'];
        unset($params['sign']);
        ksort($params);
        $string = urldecode(http_build_query($params)) . '&key=' . $app_key;
        hash_equals($sign, md5($string)) or
        throwBaseException($GLOBALS['__API_ERROR_CODE'][API_ERROR_CODE_INVALID_SIGN], API_ERROR_CODE_INVALID_SIGN);

    }

    /**
     * 签名
     * @param array $params 业务参数
     * @param string $method 方法名称
     * @return array
     */
    public static function sign($params, $method): array
    {
        $params['app_id'] = params('open.app_id');
        $params['sign_type'] = params('open.sign_type');
        $params['version'] = params('open.version');
        $params['method'] = $method;
        ksort($params);
        $string = urldecode(http_build_query($params)) . '&key=' . params('open.app_key');
        $params['sign'] = md5($string);
        return $params;
    }
}