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
     * @var array $messages
     */
    private static $messages;

    /**
     * 判断数据是否json格式
     * @param string $data
     * @return bool
     */
    public static function isJson(string &$data): bool
    {
        $data = json_decode($data, true);
        if (is_array($data) || is_object($data)) return true;
        return false;
    }

    /**
     * 校验数组必填字段是否为空
     * @param array $params
     * @param array $check_params
     * @throws Exception
     */
    public static function checkValidEmpty(array $params, array $check_params)
    {
        $result = [];
        foreach ($check_params as $v) {
            empty($params[$v]) and $result[] = $v;
        }
        empty($result) or
        throwBaseException($GLOBALS['__API_ERROR_CODE'][API_ERROR_CODE_LACK_PARAMS] . ':' . implode(',', $result), API_ERROR_CODE_LACK_PARAMS);
    }

    /**
     * 验证参数
     * @param array $params
     * @param array $rules
     * @param array $messages
     * @throws Exception
     */
    public static function make(array $params, array $rules, array $messages = [])
    {
        self::$params = $params;
        self::$messages = $messages;
        foreach ($rules as $key => $rule) {
            $rule_array = explode('|', $rule);
            foreach ($rule_array as $rule_item) {
                @list($item, $value) = explode(':', $rule_item);
                method_exists(self::class, $item) or throwBaseException('Validator rule ' . $item . ' is undefined');
                $params = [$key];
                is_null($value) or $params[] = $value;
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
        empty(self::$params[$key]) and
        throwBaseException(self::$messages[$key . '.required'] ?? 'Parameter ' . $key . ' cannot be empty');
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
        is_numeric(self::$params[$key]) or
        throwBaseException(self::$messages[$key . '.numeral'] ?? 'Parameter ' . $key . ' must be numeric');
    }

    /**
     * 最小长度
     * @param $key
     * @param $value
     * @throws Exception
     */
    private static function min($key, $value)
    {
        mb_strlen(self::$params[$key], 'utf-8') < $value and
        throwBaseException(self::$messages[$key . '.min'] ?? 'The length of parameter ' . $key . ' cannot be less than ' . $value);
    }

    /**
     * 最大长度
     * @param $key
     * @param $value
     * @throws Exception
     */
    private static function max($key, $value)
    {
        mb_strlen(self::$params[$key], 'utf-8') > $value and
        throwBaseException(self::$messages[$key . '.max'] ?? 'The length of parameter ' . $key . ' cannot be longer than ' . $value);
    }
}