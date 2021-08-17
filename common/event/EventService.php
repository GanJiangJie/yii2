<?php

namespace app\common\event;

use app\common\event\model\DefaultEvent;
use app\common\listen\model\DefaultListen;

class EventService
{
    /**
     * @var array $events 注册事件
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