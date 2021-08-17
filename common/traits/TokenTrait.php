<?php

namespace app\common\traits;

use app\common\util\Redis\RedisK;
use app\common\util\Redis\RedisS;

trait TokenTrait
{
    use InstanceTrait;

    /**
     * @var array $data
     */
    private $data;

    /**
     * @var string $driver
     */
    private $driver;

    /**
     * @var string $prefix
     */
    private $prefix;

    /**
     * @var string $name
     */
    private $name;

    /**
     * 设置令牌
     * @param array $data
     * @return string
     */
    public function setToken($data)
    {
        $data_json = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $token = self::token($data_json);
        redis(RedisS::class, 'Set', [$this->prefix . $token, $data_json], $this->driver);
        return $token;
    }

    /**
     * 设置令牌，设置期限
     * @param $data
     * @param $seconds
     * @return string
     */
    public function setExToken($data, $seconds)
    {
        $data_json = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $token = self::token($data_json);
        redis(RedisS::class, 'SetEx', [$this->prefix . $token, $data_json, $seconds], $this->driver);
        return $token;
    }

    /**
     * 生成令牌
     * @param $data_json
     * @return string
     */
    private function token($data_json)
    {
        return md5($data_json . time() . mt_rand(100, 999));
    }

    /**
     * 获取令牌信息
     * @param string $key
     * @return null|string|array
     */
    public function getToken($key = null)
    {
        if (empty($this->data)) {
            $token = request()->params($this->name);
            if (empty($token)) return null;
            $info_json = redis(RedisS::class, 'Get', [$this->prefix . $token], $this->driver);
            $this->data = json_decode($info_json, true) ?: [];
        }
        return is_null($key) ? $this->data : ($this->data[$key] ?? null);
    }

    /**
     * 删除令牌
     * @return bool|string
     */
    public function delToken()
    {
        $token = request()->params($this->name);
        if (empty($token)) return false;
        return redis(RedisK::class, 'Del', [$this->prefix . $token], $this->driver);
    }
}