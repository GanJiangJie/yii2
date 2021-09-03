<?php

namespace app\common\event;

use app\common\event\model\{
    DefaultEvent,
    MemberRegisterEvent
};
use app\common\listen\model\{
    DefaultListen,
    EmailListen
};

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
        MemberRegisterEvent::class => [
            EmailListen::class,
        ],
    ];
}