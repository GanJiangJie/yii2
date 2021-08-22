<?php

namespace common\util\DataCheck;

use yii\base\Exception;

class Validator
{
    /**
     * @var array $params
     */
    private static $params;

    /**
     * 校验数组必填字段是否为空
     * @param $params
     * @param $check_params
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
            $message = $GLOBALS['__API_ERROR_CODE'][API_ERROR_CODE_LACK_PARAMS] . ':' . implode(',', $result);
            throw new Exception($message, API_ERROR_CODE_LACK_PARAMS);
        }
    }

    /**
     * 验证参数
     * @param array $params
     * @param array $rules
     * @throws Exception
     */
    public static function make($params, $rules)
    {
        self::$params = $params;
        foreach ($rules as $key => $rule) {
            $rule_array = explode('|', $rule);
            foreach ($rule_array as $rule_item) {
                @list($item, $value) = explode(':', $rule_item);
                if (!method_exists(self::class, $item)) {
                    throw new Exception('Validator rule ' . $item . ' is undefined');
                }
                $params = [$key];
                if (!is_null($value)) {
                    $params[] = $value;
                }
                call_user_func_array([self::class, $item], $params);
            }
        }
    }

    /**
     * 必填
     * @param $key
     * @throws Exception
     */
    private static function required($key)
    {
        if (empty(self::$params[$key])) {
            throw new Exception('Parameter ' . $key . ' cannot be empty');
        }
    }

    /**
     * 可为空
     * @param $key
     */
    private static function nullable($key)
    {
    }

    /**
     * 数字
     * @param $key
     * @throws Exception
     */
    private static function numeral($key)
    {
        if (!is_numeric(self::$params[$key])) {
            throw new Exception('Parameter ' . $key . ' must be numeric');
        }
    }

    /**
     * 最小长度
     * @param $key
     * @param $value
     * @throws Exception
     */
    private static function min($key, $value)
    {
        if (mb_strlen(self::$params[$key], 'utf-8') < $value) {
            throw new Exception('The length of parameter ' . $key . ' cannot be less than ' . $value);
        }
    }

    /**
     * 最大长度
     * @param $key
     * @param $value
     * @throws Exception
     */
    private static function max($key, $value)
    {
        if (mb_strlen(self::$params[$key], 'utf-8') > $value) {
            throw new Exception('The length of parameter ' . $key . ' cannot be longer than ' . $value);
        }
    }
}