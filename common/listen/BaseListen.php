<?php

namespace app\common\listen;

/**
 * 监听
 * Class BaseListen
 * @package app\common\listen
 */
abstract class BaseListen
{
    /**
     * 执行参数
     * @var array $params
     */
    public $params;

    /**
     * 异步执行
     * @var bool $async
     */
    public $async = false;

    /**
     * 参数挂载
     * BaseListen constructor.
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * 执行方法
     * @return array
     */
    abstract public function handle(): array;
}