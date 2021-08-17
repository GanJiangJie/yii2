<?php

namespace app\common\listen\model;

use app\common\listen\BaseListen;

/**
 * 默认监听
 * Class DefaultListener
 * @package app\common\listener
 */
class DefaultListen extends BaseListen
{
    /**
     * @var bool $async
     */
    public $async = true;

    /**
     * @return array|mixed
     */
    public function handle()
    {
        //异步多表操作建议使用事务

        //Do Something With $this->params

        return ['status' => true];
        //return ['status' => true,'msg' => ''];
        //return ['status' => false,'msg' => ''];
    }
}