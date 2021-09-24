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
    /**
     * @return array
     */
    public function handle(): array
    {
        return [];
    }
}