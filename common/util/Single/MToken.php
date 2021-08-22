<?php

namespace app\common\util\Single;

use app\common\traits\TokenTrait;

class MToken
{
    use TokenTrait;

    private $m = 'auth.mToken.';

    /**
     * MToken constructor.
     */
    private function __construct()
    {
        $this->driver = params($this->m . 'driver');
        $this->prefix = params($this->m . 'prefix');
        $this->name = params($this->m . 'name');
    }
}