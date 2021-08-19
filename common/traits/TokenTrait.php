<?php

namespace app\common\traits;

use app\common\util\Redis\RedisK;
use app\common\util\Redis\RedisS;
use yii\base\Exception;

trait TokenTrait
{
    use InstanceTrait;

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
     * @var array $data
     */
    private $data;

    /**
     * 设置令牌
     * @param array $data
     * @return string
     */
    public function set($data)
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
    public function setEx($data, $seconds)
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
     * @throws Exception
     */
    public function get($key = null)
    {
        if (empty($this->data)) {
            $token = request()->params($this->name);
            if (empty($token)) {
                throw new Exception($GLOBALS['__API_ERROR_CODE'][API_ERROR_CODE_INVALID_TOKEN], API_ERROR_CODE_INVALID_TOKEN);
            }
            $info_json = redis(RedisS::class, 'Get', [$this->prefix . $token], $this->driver);
            if (empty($info_json)) {
                throw new Exception($GLOBALS['__API_ERROR_CODE'][API_ERROR_CODE_INVALID_TOKEN], API_ERROR_CODE_INVALID_TOKEN);
            }
            $this->data = json_decode($info_json, true) ?: [];
        }
        return is_null($key) ? $this->data : ($this->data[$key] ?? null);
    }

    /**
     * 删除令牌
     * @return bool|string
     */
    public function del()
    {
        $token = request()->params($this->name);
        if (empty($token)) return false;
        return redis(RedisK::class, 'Del', [$this->prefix . $token], $this->driver);
    }
}