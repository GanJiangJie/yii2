<?php

namespace app\common\traits;

trait SingleTrait
{
    use InstanceTrait;

    private function __construct()
    {
    }
}