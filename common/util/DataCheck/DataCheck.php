<?php

namespace common\util\DataCheck;

use app\common\util\Redis\RedisS;
use yii\base\Exception;

class DataCheck extends DataCheckBase
{
    /**
     * 验签
     * @param array $params
     * @throws Exception
     */
    public static function checkSign($params)
    {
        if (empty($params['sign'])) {
            throw new Exception($GLOBALS['__API_ERROR_CODE'][API_ERROR_CODE_LACK_SIGN], API_ERROR_CODE_LACK_SIGN);
        }
        $app_key = redis(RedisS::class, 'Get', [$params['app_id']]);
        if (empty($app_key)) {
            throw new Exception($GLOBALS['__API_ERROR_CODE'][API_ERROR_CODE_INVALID_APP_ID], API_ERROR_CODE_INVALID_APP_ID);
        }
        $sign = $params['sign'];
        unset($params['sign']);
        ksort($params);
        $string = urldecode(http_build_query($params)) . '&key=' . $app_key;
        if (!hash_equals($sign, md5($string))) {
            throw new Exception($GLOBALS['__API_ERROR_CODE'][API_ERROR_CODE_INVALID_SIGN], API_ERROR_CODE_INVALID_SIGN);
        }
    }

    /**
     * 签名
     * @param array $params 业务参数
     * @param string $method 方法名称
     * @return array
     */
    public static function sign($params, $method)
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