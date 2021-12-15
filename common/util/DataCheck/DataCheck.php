<?php

namespace common\util\DataCheck;

use app\components\Exception;

class DataCheck
{
    /**
     * 校验版本号
     * @param string $version
     * @throws Exception
     */
    public static function version(string $version)
    {
        if ($version != config('params.open.version')) {
            throw new Exception('', API_ERROR_CODE_INVALID_VERSION);
        }
    }

    /**
     * 校验签名类型
     * @param string $sign_type
     * @throws Exception
     */
    public static function signType(string $sign_type)
    {
        if ($sign_type != config('params.open.sign_type')) {
            throw new Exception('', API_ERROR_CODE_INVALID_SIGN_TYPE);
        }
    }

    /**
     * 验签
     * @param array $params
     * @throws Exception
     */
    public static function checkSign(array $params)
    {
        $app_key = redis(config('params.token.driver'))->get($params['app_id']);
        if (empty($app_key)) {
            throw new Exception('', API_ERROR_CODE_INVALID_APP_ID);
        }
        $sign = $params['sign'];
        unset($params['sign']);
        ksort($params);
        $string = urldecode(http_build_query($params)) . '&key=' . $app_key;
        if (!hash_equals($sign, md5($string))) {
            throw new Exception('', API_ERROR_CODE_INVALID_SIGN);
        }
    }

    /**
     * 签名
     * @param string $method 方法名称
     * @param array $params 业务参数
     * @return array
     */
    public static function sign(string $method, array $params): array
    {
        $config = config('params.open');
        $params['app_id'] = $config['app_id'];
        $params['sign_type'] = $config['sign_type'];
        $params['version'] = $config['version'];
        $params['method'] = $method;
        ksort($params);
        $string = urldecode(http_build_query($params)) . '&key=' . $config['app_key'];
        $params['sign'] = md5($string);
        return $params;
    }
}