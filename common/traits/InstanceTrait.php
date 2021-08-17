<?php

namespace app\common\traits;

trait InstanceTrait
{
    private static $instance;

    private function __clone()
    {
    }

    public static function instance()
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}