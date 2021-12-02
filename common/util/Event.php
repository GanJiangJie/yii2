<?php

namespace app\common\util;

use app\common\event\BaseEvent;
use app\common\listen\BaseListen;
use app\common\event\EventService;

class Event extends EventService
{
    /**
     * 挂起事件
     * @param BaseEvent $event_instance
     * @return array
     */
    public static function hangup($event_instance): array
    {
        /**
         * 获取全部注册事件
         * @var array $events
         */
        $events = self::$events;
        /**
         * 获取事件实例类名
         * @var string $event_class
         */
        $event_class = get_class($event_instance);
        if (!class_exists($event_class)) {
            return [
                'status' => false,
                'msg' => '\'' . $event_class . '\'类不存在'
            ];
        }
        if (!isset($events[$event_class])) {
            return [
                'status' => false,
                'msg' => '\'' . $event_class . '\'没有注册'
            ];
        }
        if (!is_array($events[$event_class])) {
            return [
                'status' => false,
                'msg' => '\'' . $event_class . '\'注册错误'
            ];
        }
        //遍历事件全部监听
        foreach ($events[$event_class] as $listen_class) {
            if (!class_exists($listen_class)) {
                return [
                    'status' => false,
                    'msg' => '\'' . $listen_class . '\'类不存在'
                ];
            }
            /**
             * 实例化监听器
             * @var BaseListen $listen_instance
             */
            $listen_instance = new $listen_class($event_instance->data);
            /**
             * 执行监听方法
             * @var array $result
             */
            $result = $listen_instance->handle();
            if (isset($result['status']) && !$result['status']) {
                return $result;
            }
        }
        return ['status' => true];
    }

    /**
     * 监听方法执行
     * @param BaseListen $listen_instance
     * @return array
     */
    public static function handle($listen_instance): array
    {
        /**
         * @var array $result
         */
        $result = $listen_instance->handle();
        if (isset($result['status'])) {
            return $result;
        }
        return ['status' => true];
    }
}