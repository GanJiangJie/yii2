<?php

namespace app\common\util\Single;

use app\common\traits\TokenTrait;

class UToken
{
    use TokenTrait;

    private $u = 'auth.uToken.';

    /**
     * MToken constructor.
     */
    private function __construct()
    {
        $this->driver = params($this->u . 'driver');
        $this->prefix = params($this->u . 'prefix');
        $this->name = params($this->u . 'name');
    }
}