<?php

namespace app\common\util\Single;

use app\common\traits\InstanceTrait;

class Request
{
    use InstanceTrait;

    /**
     * 客户端IP
     * @var string $clientIp
     */
    public $clientIp;

    /**
     * 请求头信息
     * @var array $header
     */
    public $header;

    /**
     * 请求参数
     * @var array $params
     */
    public $params;

    /**
     * 上传文件
     * @var array $files
     */
    public $files;

    /**
     * Request constructor.
     */
    private function __construct()
    {
        //$this->clientIp = self::getClientIp();
        //$this->header = getallheaders();
        $this->params = self::getParams();
        $this->files = $_FILES;
    }

    /**
     * 获取客户端IP地址
     * @return null|string
     */
    private function getClientIp()
    {
        // check for shared internet/ISP IP
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }
        // check for IPs passing through proxies
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // check if multiple ips exist in var
            @list($ip) = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            return $ip;
        }
        if (isset($_SERVER['HTTP_X_FORWARDED'])) {
            return $_SERVER['HTTP_X_FORWARDED'];
        }
        if (isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
            return $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
        }
        if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_FORWARDED_FOR'];
        }
        if (isset($_SERVER['HTTP_FORWARDED'])) {
            return $_SERVER['HTTP_FORWARDED'];
        }
        if (isset($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        }
        return null;
    }

    /**
     * 获取请求参数
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
     * 获取客户端IP地址
     * @return null|string
     */
    public function clientIp()
    {
        return $this->clientIp;
    }

    /**
     * 获取请求头的信息
     * @param string $key
     * @param string|int $default
     * @return null|string|array
     */
    public function header(string $key = null, $default = null)
    {
        return is_null($key) ? $this->header : ($this->header[$key] ?? $default);
    }

    /**
     * 获取请求参数
     * @param string $key
     * @param string|int $default
     * @return mixed
     */
    public function params(string $key = null, $default = null)
    {
        return is_null($key) ? $this->params : ($this->params[$key] ?? $default);
    }

    /**
     * 获取上传文件
     * @param string $key
     * @return null|array
     */
    public function files(string $key = null)
    {
        return is_null($key) ? $this->files : ($this->files[$key] ?? null);
    }
}