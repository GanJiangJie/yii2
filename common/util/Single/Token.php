<?php

namespace app\common\util\Single;

use app\common\traits\InstanceTrait;
use common\util\DataCheck\Validator;
use app\common\util\Redis\{
    RedisK,
    RedisS
};
use yii\base\Exception;

class Token
{
    use InstanceTrait;

    /**
     * @var string $driver
     */
    private $driver;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string $token
     */
    public $token;

    /**
     * @var bool $status
     */
    public $status = false;

    /**
     * @var array $data
     */
    public $data;

    /**
     * Token constructor.
     */
    private function __construct()
    {
        $this->driver = config('params.token.driver', 'redis');
        $this->name = config('params.token.name', 'token');
        $this->token = requestParams($this->name);
    }

    /**
     * 生成令牌
     * @param string $prefix
     */
    private function createToken(string $prefix)
    {
        $this->token = md5('token' . $prefix . time() . mt_rand(100, 999));
    }

    /**
     * 设置令牌
     * @param array $data
     * @param string $prefix
     * @return string
     * @throws Exception
     */
    public function set(array $data, string $prefix = ''): string
    {
        $this->data = $data;
        $data_json = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        self::createToken($prefix ?: $data_json);
        redis(RedisS::class, 'Set', [$this->token, $data_json], $this->driver);
        return $this->token;
    }

    /**
     * 设置令牌，设置期限
     * @param array $data
     * @param int $seconds
     * @param string $prefix
     * @return string
     * @throws Exception
     */
    public function setEx(array $data, int $seconds, string $prefix = ''): string
    {
        $this->data = $data;
        $data_json = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        self::createToken($prefix ?: $data_json);
        redis(RedisS::class, 'SetEx', [$this->token, $data_json, $seconds], $this->driver);
        return $this->token;
    }

    /**
     * 令牌校验
     * @throws Exception
     */
    public function check()
    {
        empty($this->token) and tbe('', API_ERROR_CODE_LACK_TOKEN);
        $info = redis(RedisS::class, 'Get', [$this->token], $this->driver);
        empty($info) || !Validator::isJson($info) and tbe('', API_ERROR_CODE_INVALID_TOKEN);
        $this->data = $info;
        $this->status = true;
    }

    /**
     * 获取令牌信息
     * @param string $key
     * @return null|string|array
     */
    public function get(string $key = null)
    {
        return is_null($key) ? $this->data : ($this->data[$key] ?? null);
    }

    /**
     * 删除令牌
     * @param string $token
     * @return bool|string
     * @throws Exception
     */
    public function del(string $token = null)
    {
        if (!empty($token)) {
            return redis(RedisK::class, 'Del', [$token], $this->driver);
        }
        if (empty($this->token)) return false;
        return redis(RedisK::class, 'Del', [$this->token], $this->driver);
    }
}