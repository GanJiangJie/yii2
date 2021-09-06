<?php

namespace app\common\listen\model;

use app\common\listen\BaseListen;

class MemberCouponListen extends BaseListen
{
    public function handle(): array
    {
        // Do something with $this->params

        return ['status' => true];
    }
}