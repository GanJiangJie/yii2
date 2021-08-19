<?php

namespace app\common\event;

use app\common\event\model\DefaultEvent;
use app\common\listen\model\DefaultListen;

class EventService
{
    /**
     * 注册事件
     * @var array $events
     */
    protected static $events = [
        /*'app\common\event\model\DefaultEvent' => [
            'app\common\listen\model\DefaultListen',
        ],*/
        DefaultEvent::class => [
            DefaultListen::class,
        ],
    ];
}