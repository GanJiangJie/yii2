<?php

namespace app\common\util;

use app\common\traits\TokenTrait;

class MToken
{
    use TokenTrait;

    private $i = 'config.mToken';

    /**
     * MToken constructor.
     */
    private function __construct()
    {
        $this->driver = config($this->i . '.driver');
        $this->prefix = config($this->i . '.prefix');
        $this->name = config($this->i . '.name');
    }
}