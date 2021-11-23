<?php

if (!function_exists('dd')) {
    /**
     * 打印断点 相当执行：var_dump($a,$b,$c...);exit
     * @param mixed ...$vars
     */
    function dd(...$vars)
    {
        foreach ($vars as $var) {
            var_dump($var);
        }
        exit;
    }
}

if (!function_exists('tbe')) {
    /**
     * @param string $message
     * @param int $code
     * @throws \yii\base\Exception
     */
    function tbe(string $message, int $code = API_ERROR_CODE_FAIL)
    {
        @list($back, $trace) = debug_backtrace();
        exception()->backtrace($back ?? [], $trace ?? []);
        $msg = $message ?: ($GLOBALS['__API_ERROR_CODE'][$code] ?? $GLOBALS['__API_ERROR_CODE'][API_ERROR_CODE_FAIL]);
        exception()->message = $msg;
        exception()->code = $code;
        throw new \yii\base\Exception($msg, $code);
    }
}

if (!function_exists('exception')) {
    /**
     * @return \app\common\util\Single\Exception
     */
    function exception(): \app\common\util\Single\Exception
    {
        return \app\common\util\Single\Exception::instance();
    }
}

if (!function_exists('app')) {
    /**
     * @return \yii\console\Application|\yii\web\Application
     */
    function app()
    {
        return Yii::$app;
    }
}

if (!function_exists('db')) {
    /**
     * @param string $db
     * @return \yii\db\Connection
     */
    function db(string $db = 'db'): \yii\db\Connection
    {
        return app()->$db;
    }
}

if (!function_exists('config')) {
    /**
     * 获取config目录下的参数
     * @param string $key 例：'params.queue.listen'
     * @param $default
     * @return mixed
     */
    function config(string $key, $default = null)
    {
        /**
         * @var \app\common\util\Single\Config $config
         */
        $config = \app\common\util\Single\Config::instance();
        return $config->get($key) ?: $default;
    }
}

if (!function_exists('arraySeriesIndex')) {
    /**
     * 数组$array_index全部元素作为数组$array_subject多级索引
     * @param array $array_subject
     * @param array $array_index
     * @return mixed
     */
    function arraySeriesIndex(array $array_subject, array $array_index)
    {
        foreach ($array_index as $item) {
            if (!is_array($array_subject)) return null;
            $array_subject = $array_subject[$item] ?? null;
        }
        return $array_subject;
    }
}

if (!function_exists('redis')) {
    /**
     * Redis辅助方法
     * @param string $class
     * @param string $method
     * @param array $params
     * @param string $redis
     * @return mixed
     * @throws \yii\base\Exception
     */
    function redis(string $class, string $method, array $params, string $redis = 'redis')
    {
        method_exists($class, $method) or tbe('Undefined method \'' . $method . '\' of class \'' . $class . '\'');
        \app\common\util\Redis\RedisBase::$redis = $redis;
        return call_user_func_array([$class, $method], $params);
    }
}

if (!function_exists('event')) {
    /**
     * 事件挂起
     * @param \app\common\event\BaseEvent $event_instance
     * @return array
     * @throws \yii\base\Exception
     */
    function event($event_instance): array
    {
        $result = \app\common\util\Event::hangup($event_instance);
        $result['status'] or tbe($result['msg']);
        return $result;
    }
}

if (!function_exists('listenHandle')) {
    /**
     * 监听处理
     * @param \app\common\listen\BaseListen $listen_instance
     * @return array
     * @throws \yii\base\Exception
     */
    function listenHandle($listen_instance): array
    {
        $result = \app\common\util\Event::handle($listen_instance);
        $result['status'] or tbe($result['msg']);
        return $result;
    }
}

if (!function_exists('route')) {
    /**
     * @return \app\common\util\Single\Route
     */
    function route(): \app\common\util\Single\Route
    {
        return \app\common\util\Single\Route::instance();
    }
}

if (!function_exists('request')) {
    /**
     * HTTPS请求对象
     * @return \app\common\util\Single\Request
     */
    function request(): \app\common\util\Single\Request
    {
        return \app\common\util\Single\Request::instance();
    }
}

if (!function_exists('requestParams')) {
    /**
     * HTTPS请求参数
     * @param string $key
     * @param string|int $default
     * @return array|null|string
     */
    function requestParams(string $key = null, $default = null)
    {
        return request()->params($key, $default);
    }
}

if (!function_exists('token')) {
    /**
     * 登录令牌
     * @return \app\common\util\Single\Token
     */
    function token(): \app\common\util\Single\Token
    {
        return \app\common\util\Single\Token::instance();
    }
}

if (!function_exists('tokenGet')) {
    /**
     * 令牌信息
     * @param string $key
     * @return array|null|string
     */
    function tokenGet(string $key = null)
    {
        return token()->get($key);
    }
}

if (!function_exists('response')) {
    /**
     * @return \app\common\util\Single\Response
     */
    function response(): \app\common\util\Single\Response
    {
        return \app\common\util\Single\Response::instance();
    }
}

if (!function_exists('getDirFile')) {
    /**
     * 获取文件夹下全部文件
     * @param string $path 文件夹的路径: BASE_PATH . '/common'
     * @param bool $flag true返回文件路径, false返回文件名称
     * @return array
     */
    function getDirFile(string $path, bool $flag = false): array
    {
        return \app\common\util\DirFile::getDirFile($path, $flag);
    }
}

if (!function_exists('delDirFile')) {
    /**
     * 删除目录、文件
     * @param string $path
     * @return bool
     */
    function delDirFile(string $path)
    {
        return \app\common\util\DirFile::delDirFile($path);
    }
}

if (!function_exists('logPrint')) {
    /**
     * 日志打印
     * @return \common\util\Single\Log
     */
    function logPrint(): \common\util\Single\Log
    {
        return \common\util\Single\Log::instance();
    }
}

if (!function_exists('createCode')) {
    /**
     * 生成随机且不重复12位编号
     * @param \yii\db\ActiveRecord $model
     * @param string $attribute
     * @param int $length
     * @return string
     */
    function createCode(\yii\db\ActiveRecord $model, string $attribute, int $length = 12): string
    {
        $start = (int)str_pad('1', $length, '0');
        $end = (int)str_pad('9', $length, '9');
        do {
            $code = (string)mt_rand($start, $end);
        } while (
            $model::find()
                ->where($attribute . ' = :' . $attribute, [
                    ':' . $attribute => $code
                ])
                ->exists()
        );
        return $code;
    }
}

if (!function_exists('phoneHide')) {
    /**
     * 手机号码：'13099417612' => '130****7612'
     * @param string $phone
     * @return null|string|string[]
     */
    function phoneHide($phone)
    {
        return preg_replace('/(\d{3})\d{4}(\d{4})/', '$1****$2', $phone);
    }
}

if (!function_exists('emailHide')) {
    /**
     * 邮箱：'13099417612@163.com' => '1309940****@163.com'
     * @param string $email
     * @return null|string|string[]
     */
    function emailHide($email)
    {
        return preg_replace('/([a-z0-9\-_\.]+)[a-z0-9\-_\.]{4}(@[a-z0-9]+\.[a-z0-9\-_\.]+)/', '$1****$2', $email);
    }
}

if (!function_exists('daysCount')) {
    /**
     * 计算两个日期之间天数
     * @param string $start_time 'Y-m-d'
     * @param string $end_time 'Y-m-d'
     * @return int
     */
    function daysCount($start_time, $end_time): int
    {
        $a_dt = getdate(strtotime($start_time));
        $b_dt = getdate(strtotime($end_time));
        $a_new = mktime($a_dt['hours'], $a_dt['minutes'], $a_dt['seconds'], $a_dt['mon'], $a_dt['mday'], $a_dt['year']);
        $b_new = mktime($b_dt['hours'], $b_dt['minutes'], $b_dt['seconds'], $b_dt['mon'], $b_dt['mday'], $b_dt['year']);
        return (int)round(($b_new - $a_new) / 86400);
    }
}

if (!function_exists('birthdayToAge')) {
    /**
     * 根据生日计算年龄
     * @param string $birthday 'Y-m-d'
     * @return int
     */
    function birthdayToAge(string $birthday): int
    {
        return date_diff(date_create(date('Y-m-d')), date_create($birthday))->y;
    }
}

if (!function_exists('secondsDHIS')) {
    /**
     * 秒数格式化，将秒数转化为：日数、时数、分数、秒数
     * @param $seconds
     * @param bool $d
     * @param bool $h
     * @param bool $i
     * @param bool $s
     * @return array
     */
    function secondsDHIS($seconds, $d = true, $h = true, $i = true, $s = true): array
    {
        $seconds_int = intval($seconds);
        $data = [
            'days' => 0,
            'hours' => 0,
            'minutes' => 0,
            'seconds' => 0
        ];
        if ($d) {
            if ($seconds_int > 86400) {
                $data['days'] = (int)bcdiv($seconds_int, 86400);
                $seconds_int = bcmod($seconds_int, 86400);
            }
        } else {
            unset($data['days']);
        }
        if ($h) {
            if ($seconds_int > 3600) {
                $data['hours'] = (int)bcdiv($seconds_int, 3600);
                $seconds_int = bcmod($seconds_int, 3600);
            }
        } else {
            unset($data['hours']);
        }
        if ($i) {
            if ($seconds_int > 60) {
                $data['minutes'] = (int)bcdiv($seconds_int, 60);
                $seconds_int = bcmod($seconds_int, 60);
            }
        } else {
            unset($data['minutes']);
        }
        if ($s) {
            if ($seconds_int > 0) {
                $data['seconds'] = (int)$seconds_int;
            }
        } else {
            unset($data['seconds']);
        }
        return $data;
    }
}

if (!function_exists('xmlToArray')) {
    /**
     * xml转数组
     * @param $xml
     * @return mixed
     */
    function xmlToArray($xml)
    {
        libxml_disable_entity_loader(true);
        return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }
}

if (!function_exists('yuanConvertCent')) {
    /**
     * 元转为分
     * @param $yuan
     * @return string
     */
    function yuanConvertCent($yuan)
    {
        return bcmul($yuan, 100);
    }
}

if (!function_exists('centConvertYuan')) {
    /**
     * 分转为元
     * @param $cent
     * @return string
     */
    function centConvertYuan($cent)
    {
        return bcdiv($cent, 100, 2);
    }
}