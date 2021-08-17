<?php

namespace app\common\util;

use app\common\traits\TokenTrait;

class MToken
{
    use TokenTrait;

    /**
     * MToken constructor.
     */
    private function __construct()
    {
        $i = 'config.mToken';
        @list($this->driver, $this->prefix, $this->name) = config([$i . '.driver', $i . '.prefix', $i . '.name']);
    }
}