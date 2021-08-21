<?php

namespace app\common\event;

use app\common\event\model\DefaultEvent;
use app\common\event\model\MemberRegisterEvent;
use app\common\listen\model\DefaultListen;
use app\common\listen\model\EmailListen;

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