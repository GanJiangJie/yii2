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
        self::$instance instanceof self or self::$instance = new self();
        return self::$instance;
    }
}