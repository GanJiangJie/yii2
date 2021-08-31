<?php

namespace app\common\util\Single;

use app\common\traits\InstanceTrait;

class Request
{
    use InstanceTrait;

    /**
     * @var array $request
     */
    private $request;

    /**
     * Request constructor.
     */
    private function __construct()
    {
        $this->request = [
            'header' => getallheaders(),
            'param' => self::getParams(),
            'file' => $_FILES
        ];
    }

    /**
     * @return array
     */
    private function getParams(): array
    {
        if (app()->request->isGet) return app()->request->get();
        if (app()->request->isPost) return array_map(function ($value) {
            return urldecode($value);
        }, app()->request->post());
        return [];
    }

    /**
     * 获取请求头的信息
     * @param string $key
     * @param string|int $default
     * @return null|string|array
     */
    public function header(string $key = null, $default = null)
    {
        return is_null($key) ? $this->request['header'] : ($this->request['header'][$key] ?? $default);
    }

    /**
     * 获取请求参数
     * @param string $key
     * @param string|int $default
     * @return null|string|array
     */
    public function param(string $key = null, $default = null)
    {
        return is_null($key) ? $this->request['param'] : ($this->request['param'][$key] ?? $default);
    }

    /**
     * 获取上传文件
     * @param string $key
     * @return null|array
     */
    public function file(string $key = null)
    {
        return is_null($key) ? $this->request['file'] : ($this->request['file'][$key] ?? null);
    }
}