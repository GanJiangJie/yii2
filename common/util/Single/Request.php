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
            'headers' => getallheaders(),
            'params' => self::getParams(),
            'files' => $_FILES
        ];
    }

    /**
     * @return array
     */
    private function getParams(): array
    {
        if (app()->request->isGet) {
            return app()->request->get();
        }
        if (app()->request->isPost) {
            return array_map(function ($value) {
                return urldecode($value);
            }, app()->request->post());
        }
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
        return is_null($key) ? $this->request['headers'] : ($this->request['headers'][$key] ?? $default);
    }

    /**
     * 获取请求参数
     * @param string $key
     * @param string|int $default
     * @return null|string|array
     */
    public function params(string $key = null, $default = null)
    {
        return is_null($key) ? $this->request['params'] : ($this->request['params'][$key] ?? $default);
    }

    /**
     * 获取上传文件
     * @param string $key
     * @return null|array
     */
    public function files(string $key = null)
    {
        return is_null($key) ? $this->request['files'] : ($this->request['files'][$key] ?? null);
    }
}