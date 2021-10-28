<?php

namespace common\util\DataCheck;

use yii\base\Exception;
use yii\db\ActiveRecord;

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
     * @var int $code
     */
    private static $code = API_ERROR_CODE_INVALID_PARAMS;

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
    public static function notEmpty(array $params, array $check_params)
    {
        $result = [];
        foreach ($check_params as $v) {
            empty($params[$v]) and $result[] = $v;
        }
        empty($result) or
        tbe($GLOBALS['__API_ERROR_CODE'][API_ERROR_CODE_LACK_PARAMS] . ':' . implode(',', $result), API_ERROR_CODE_LACK_PARAMS);
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
                method_exists(self::class, $item) or tbe('Validator rule ' . $item . ' is undefined');
                if ($value) {
                    self::$item($key, $value);
                    continue;
                }
                self::$item($key);
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
        tbe(self::$messages[$key . '.required'] ?? 'Parameter ' . $key . ' cannot be empty', self::$code);
    }

    /**
     * 可为空
     * @param $key
     */
    private function nullable($key)
    {
    }

    /**
     * 数字
     * @param $key
     * @throws Exception
     */
    private static function numeral($key)
    {
        if (isset(self::$params[$key])) {
            is_numeric(self::$params[$key]) or
            tbe(self::$messages[$key . '.numeral'] ?? 'Parameter ' . $key . ' must be numeric', self::$code);
        }
    }

    /**
     * 最小数值
     * @param $key
     * @param $value
     * @throws Exception
     */
    private static function min($key, $value)
    {
        self::numeral($key);
        isset(self::$params[$key]) && self::$params[$key] < $value and
        tbe(self::$messages[$key . '.min'] ?? 'The value of parameter ' . $key . ' cannot be less than ' . $value, self::$code);
    }

    /**
     * 最大数值
     * @param $key
     * @param $value
     * @throws Exception
     */
    private static function max($key, $value)
    {
        self::numeral($key);
        isset(self::$params[$key]) && self::$params[$key] > $value and
        tbe(self::$messages[$key . '.min'] ?? 'The value of parameter ' . $key . ' cannot be greater than ' . $value, self::$code);
    }

    /**
     * 最小长度
     * @param $key
     * @param $value
     * @throws Exception
     */
    private static function lenMin($key, $value)
    {
        isset(self::$params[$key]) && mb_strlen(self::$params[$key], 'utf-8') < $value and
        tbe(self::$messages[$key . '.lenMin'] ?? 'The length of parameter ' . $key . ' cannot be less than ' . $value, self::$code);
    }

    /**
     * 最大长度
     * @param $key
     * @param $value
     * @throws Exception
     */
    private static function lenMax($key, $value)
    {
        isset(self::$params[$key]) && mb_strlen(self::$params[$key], 'utf-8') > $value and
        tbe(self::$messages[$key . '.lenMax'] ?? 'The length of parameter ' . $key . ' cannot be longer than ' . $value, self::$code);
    }

    /**
     * 正则表达式
     * @param $key
     * @param $value
     * @throws Exception
     */
    private static function regex($key, $value)
    {
        if (isset(self::$params[$key])) {
            preg_match($value, self::$params[$key]) or
            tbe(self::$messages[$key . '.regex'] ?? 'Parameter ' . $key . ' is invalid', self::$code);
        }
    }

    /**
     * 在指定值当中
     * @param $key
     * @param $value
     * @throws Exception
     */
    private static function in($key, $value)
    {
        if (isset(self::$params[$key])) {
            in_array(self::$params[$key], explode(',', $value)) or
            tbe(self::$messages[$key . '.in'] ?? 'The value of parameter ' . $key . ' should be in ' . $value, self::$code);
        }
    }

    /**
     * 判断参数的值在表里面是否存在
     * @param $key
     * @param $value
     * @throws Exception
     */
    private static function exists($key, $value)
    {
        if (isset(self::$params[$key])) {
            @list($modelClass, $column) = explode(',', $value);
            /**
             * @var ActiveRecord $modelClass
             */
            $modelClass::find()
                ->where($column . ' = :' . $column, [
                    ':' . $column => self::$params[$key]
                ])->exists() or
            tbe(self::$messages[$key . '.exists'] ?? 'The selected ' . $key . ' is invalid', self::$code);
        }
    }
}