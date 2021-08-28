<?php

namespace app\common\listen\model;

use app\common\listen\BaseListen;

/**
 * Send Email To Member
 * Class EmailListen
 * @package app\common\listen\model
 */
class EmailListen extends BaseListen
{
    public $async = true;

    /**
     * @return array
     */
    public function handle(): array
    {
        return [];
    }
}