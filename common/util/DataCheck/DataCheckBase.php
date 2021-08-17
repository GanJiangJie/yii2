<?php

namespace common\util\DataCheck;

use Yii;
use yii\base\Exception;

class DataCheckBase
{
    /**
     * 校验数组必填字段是否为空
     * @param array $params
     * @param array $check_params 校验项数组
     * @throws Exception
     */
    public static function checkValidEmpty($params, $check_params)
    {
        $result = [];
        foreach ($check_params as $v) {
            if (empty($params[$v])) {
                $result[] = $v;
            }
        }
        if (!empty($result)) {
            throw new Exception($GLOBALS['__API_ERROR_CODE'][API_ERROR_CODE_LACK_PARAMS] . ':' . implode(',', $result), API_ERROR_CODE_LACK_PARAMS);
        }
    }

    /**
     * 校验签名类型
     * @param string $sign_type
     * @throws Exception
     */
    public static function checkSignType($sign_type)
    {
        if ($sign_type != Yii::$app->params['open']['sign_type']) {
            throw new Exception($GLOBALS['__API_ERROR_CODE'][API_ERROR_CODE_INVALID_SIGN_TYPE], API_ERROR_CODE_INVALID_SIGN_TYPE);
        }
    }

    /**
     * 校验版本号
     * @param string $version
     * @throws Exception
     */
    public static function checkVersion($version)
    {
        if ($version != Yii::$app->params['open']['version']) {
            throw new Exception($GLOBALS['__API_ERROR_CODE'][API_ERROR_CODE_INVALID_VERSION], API_ERROR_CODE_INVALID_VERSION);
        }
    }
}