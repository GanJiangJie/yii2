<?php

namespace app\common\util;

use app\common\traits\TokenTrait;

class UToken
{
    use TokenTrait;

    /**
     * MToken constructor.
     */
    private function __construct()
    {
        $i = 'config.uToken';
        @list($this->driver, $this->prefix, $this->name) = config([$i . '.driver', $i . '.prefix', $i . '.name']);
    }
}