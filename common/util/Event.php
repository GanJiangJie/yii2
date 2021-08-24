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
    public static function hangup($event_instance)
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
        if (!isset($events[$event_class])) {
            return [
                'status' => false,
                'msg' => '事件\'' . $event_class . '\'没有进行注册'
            ];
        }
        if (!is_array($events[$event_class])) {
            return [
                'status' => false,
                'msg' => '事件\'' . $event_class . '\'监听注册错误'
            ];
        }
        //遍历事件全部监听
        foreach ($events[$event_class] as $listen_class) {
            if (!class_exists($listen_class)) {
                return [
                    'status' => false,
                    'msg' => '监听\'' . $listen_class . '\'类名注册错误'
                ];
            }
            /**
             * 实例化监听器
             * @var BaseListen $listen_instance
             */
            $listen_instance = new $listen_class($event_instance->data);
            //异步监听实例
            if ($listen_instance->async) {
                $result = self::async($listen_instance);
                if (!$result['status']) {
                    return $result;
                }
                continue;
            }
            //执行监听方法
            $result = $listen_instance->handle();
            if (isset($result['status']) && !$result['status']) {
                return $result;
            }
        }
        return ['status' => true];
    }

    /**
     * 监听执行
     * @param BaseListen $listen_instance
     * @return array
     */
    public static function listen($listen_instance)
    {
        if ($listen_instance->async) {
            return self::async($listen_instance);
        }
        return self::handle($listen_instance);
    }

    /**
     * 将监听对象扔进MNS队列
     * @param BaseListen $listen_instance
     * @return array
     */
    private static function async($listen_instance)
    {
        $result = queue()->sendMessage(params('mns.queue.listen'), serialize($listen_instance));
        if (!$result['status']) {
            return [
                'status' => false,
                'msg' => $result['response']['error_msg']
            ];
        }
        return ['status' => true];
    }

    /**
     * 监听方法执行
     * @param BaseListen $listen_instance
     * @return array
     */
    public static function handle($listen_instance)
    {
        $result = $listen_instance->handle();
        if (isset($result['status'])) {
            return $result;
        }
        return ['status' => true];
    }
}