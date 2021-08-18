<?php

namespace app\common\util;

use app\common\traits\TokenTrait;

class UToken
{
    use TokenTrait;

    private $i = 'config.uToken';

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