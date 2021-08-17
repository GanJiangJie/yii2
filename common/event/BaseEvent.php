<?php

namespace app\common\event;

/**
 * 事件
 * Class BaseEvent
 * @package app\common\event
 */
class BaseEvent
{
    /**
     * 传递参数
     * @var array $data
     */
    public $data;

    /**
     * 参数挂载
     * BaseEvent constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }
}