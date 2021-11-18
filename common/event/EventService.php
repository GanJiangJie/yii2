<?php

namespace app\common\event;

class EventService
{
    /**
     * 注册事件
     * @var array $events
     */
    protected static $events = [
        'app\common\event\model\DefaultEvent' => [
            'app\common\listen\model\DefaultListen',
        ],
        'app\common\event\model\MemberRegisterEvent' => [
            'app\common\listen\model\EmailListen',
            'app\common\listen\model\MemberCouponListen',
            'app\common\listen\model\RechargeOrderListen',
        ],
    ];
}