<?php

namespace app\common\listen\model;

use app\common\listen\BaseListen;

class RechargeOrderListen extends BaseListen
{
    public function handle(): array
    {
        return ['status' => true];
    }
}