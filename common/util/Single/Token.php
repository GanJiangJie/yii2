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
     * @var string $prefix
     */
    private $prefix;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string $token
     */
    public $token;

    /**
     * @var array $data
     */
    public $data;

    /**
     * Token constructor.
     */
    private function __construct()
    {
        $this->driver = config('params.token.driver');
        $this->prefix = config('params.token.prefix');
        $this->name = config('params.token.name');
    }

    /**
     * 生成令牌
     * @param string $unique
     * @return string
     */
    private function createToken(string $unique): string
    {
        return md5($unique . time() . mt_rand(100, 999));
    }

    /**
     * 获取请求令牌
     * @throws Exception
     */
    private function getToken()
    {
        $this->token = requestParams($this->name);
        empty($this->token) and tbe('', API_ERROR_CODE_LACK_TOKEN);
    }

    /**
     * 暂存令牌信息
     * @throws Exception
     */
    public function storage()
    {
        self::getToken();
        $info_json = redis(RedisS::class, 'Get', [$this->prefix . $this->token], $this->driver);
        empty($info_json) || !Validator::isJson($info_json) and tbe('', API_ERROR_CODE_INVALID_TOKEN);
        $this->data = $info_json;
    }

    /**
     * 设置令牌
     * @param array $data
     * @param string $unique
     * @return string
     * @throws Exception
     */
    public function set(array $data, string $unique = ''): string
    {
        $data_json = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $token = self::createToken($unique ?: $data_json);
        redis(RedisS::class, 'Set', [$this->prefix . $token, $data_json], $this->driver);
        return $token;
    }

    /**
     * 设置令牌，设置期限
     * @param array $data
     * @param int $seconds
     * @param string $unique
     * @return string
     * @throws Exception
     */
    public function setEx(array $data, int $seconds, string $unique = ''): string
    {
        $data_json = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $token = self::createToken($unique ?: $data_json);
        redis(RedisS::class, 'SetEx', [$this->prefix . $token, $data_json, $seconds], $this->driver);
        return $token;
    }

    /**
     * 获取令牌信息
     * @param string $key
     * @return null|string|array
     * @throws Exception
     */
    public function get(string $key = null)
    {
        if (empty($this->data)) self::storage();
        return is_null($key) ? $this->data : ($this->data[$key] ?? null);
    }

    /**
     * 删除令牌
     * @return bool|string
     * @throws Exception
     */
    public function del()
    {
        $token = requestParams($this->name);
        if (empty($token)) return false;
        return redis(RedisK::class, 'Del', [$this->prefix . $token], $this->driver);
    }
}