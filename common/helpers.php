<?php

if (!function_exists('dd')) {
    /**
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

if (!function_exists('config')) {
    /**
     * 获取config目录下的参数
     * @param string $key 例：'params.queue.listen'
     * @return string|array|null
     */
    function config($key)
    {
        /**
         * @var \app\common\util\Config $config
         */
        $config = \app\common\util\Config::instance();
        return $config->get($key);
    }
}

if (!function_exists('arraySeriesIndex')) {
    /**
     * 数组$array_index全部元素作为数组$array_subject多级索引
     * @param array $array_subject
     * @param array $array_index
     * @return string|array
     */
    function arraySeriesIndex($array_subject, $array_index)
    {
        $subject_item = $array_subject;
        foreach ($array_index as $item) {
            if (!is_array($subject_item)) return null;
            $subject_item = $subject_item[$item] ?? null;
        }
        return $subject_item;
    }
}

if (!function_exists('redis')) {
    /**
     * Redis辅助方法
     * @param string $class
     * @param string $method
     * @param array $params
     * @param string $redis
     * @return string
     */
    function redis($class, $method, $params, $redis = 'redis')
    {
        if (!method_exists($class, $method)) return null;
        \app\common\util\Redis\RedisBase::$redis = $redis;
        $res = call_user_func_array([$class, $method], $params);
        \app\common\util\Redis\RedisBase::$redis = 'redis';
        return $res;
    }
}

if (!function_exists('queue')) {
    /**
     * 队列单例
     * @return \app\common\package\Topic\Queue
     */
    function queue()
    {
        return \app\common\package\Topic\Queue::instance();
    }
}

if (!function_exists('topic')) {
    /**
     * 主题单例
     * @return \app\common\package\Topic\Topic
     */
    function topic()
    {
        return \app\common\package\Topic\Topic::instance();
    }
}

if (!function_exists('event')) {
    /**
     * 事件挂起
     * @param \app\common\event\BaseEvent $event_instance
     * @return array
     */
    function event($event_instance)
    {
        return \app\common\util\Event::hangup($event_instance);
    }
}

if (!function_exists('listen')) {
    /**
     * 监听挂起
     * @param \app\common\listen\BaseListen $listen_instance
     * @return array
     */
    function listen($listen_instance)
    {
        return \app\common\util\Event::listen($listen_instance);
    }
}

if (!function_exists('listenHandle')) {
    /**
     * 监听处理
     * @param \app\common\listen\BaseListen $listen_instance
     * @return array
     */
    function listenHandle($listen_instance)
    {
        return \app\common\util\Event::handle($listen_instance);
    }
}

if (!function_exists('request')) {
    /**
     * 请求对象
     * @return \app\common\util\Request
     */
    function request()
    {
        return \app\common\util\Request::instance();
    }
}

if (!function_exists('mToken')) {
    /**
     * 商户登录令牌
     * @return \app\common\util\MToken
     */
    function mToken()
    {
        return \app\common\util\MToken::instance();
    }
}

if (!function_exists('uToken')) {
    /**
     * 用户登录令牌
     * @return \app\common\util\UToken
     */
    function uToken()
    {
        return \app\common\util\UToken::instance();
    }
}

if (!function_exists('response')) {
    /**
     * @return \app\common\util\Response
     */
    function response()
    {
        return \app\common\util\Response::instance();
    }
}

if (!function_exists('logPrint')) {
    /**
     * 日志打印
     * @return \common\util\Log\Log
     */
    function logPrint()
    {
        return new \common\util\Log\Log();
    }
}

if (!function_exists('hidePhone')) {
    /**
     * 手机号码：'13099417612' => '130****7612'
     * @param string $phone
     * @return null|string|string[]
     */
    function hidePhone($phone)
    {
        return preg_replace('/(\d{3})\d{4}(\d{4})/', '$1****$2', $phone);
    }
}

if (!function_exists('hideEmail')) {
    /**
     * 邮箱：'13099417612@163.com' => '1309940****@163.com'
     * @param string $email
     * @return null|string|string[]
     */
    function hideEmail($email)
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
    function daysCount($start_time, $end_time)
    {
        $a_dt = getdate(strtotime($start_time));
        $b_dt = getdate(strtotime($end_time));
        $a_new = mktime($a_dt['hours'], $a_dt['minutes'], $a_dt['seconds'], $a_dt['mon'], $a_dt['mday'], $a_dt['year']);
        $b_new = mktime($b_dt['hours'], $b_dt['minutes'], $b_dt['seconds'], $b_dt['mon'], $b_dt['mday'], $b_dt['year']);
        return (int)round(($b_new - $a_new) / 86400);
    }
}

if (!function_exists('secondsFormat')) {
    /**
     * 秒数格式化，将秒数转化为：日数、时数、分数、秒数
     * @param $seconds
     * @param bool $d
     * @param bool $h
     * @param bool $i
     * @param bool $s
     * @return array
     */
    function secondsFormat($seconds, $d = true, $h = true, $i = true, $s = true)
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
        return bcdiv($cent, 100);
    }
}