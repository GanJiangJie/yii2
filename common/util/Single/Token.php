<?php

namespace app\common\util\Single;

use app\common\traits\InstanceTrait;
use common\util\DataCheck\Validator;
use app\components\Exception;

class Token
{
    use InstanceTrait;

    /**
     * @var mixed $redis
     */
    private $redis;

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
        $config = config('params.token');
        $this->redis = redis($config['driver']);
        $this->token = params($config['name']);
    }

    /**
     * 生成令牌
     * @param $prefix
     */
    private function createToken($prefix)
    {
        $this->token = md5('token' . $prefix . time() . mt_rand(100, 999));
    }

    /**
     * 设置令牌
     * @param array $data
     * @param $prefix
     * @return string
     */
    public function set(array $data, $prefix = ''): string
    {
        $this->data = $data;
        $data_json = json_encode($this->data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        self::createToken($prefix ?: $data_json);
        $this->redis->set($this->token, $data_json);
        return $this->token;
    }

    /**
     * 设置令牌，设置期限
     * @param array $data
     * @param int $seconds
     * @param $prefix
     * @return string
     */
    public function setEx(array $data, int $seconds, $prefix = ''): string
    {
        $this->data = $data;
        $data_json = json_encode($this->data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        self::createToken($prefix ?: $data_json);
        $this->redis->setex($this->token, $seconds, $data_json);
        return $this->token;
    }

    /**
     * 令牌校验
     * @throws Exception
     */
    public function check()
    {
        if (empty($this->token)) {
            throw new Exception('', API_ERROR_CODE_LACK_TOKEN);
        }
        $info = $this->redis->get($this->token);
        if (empty($info) || !Validator::isJson($info)) {
            throw new Exception('', API_ERROR_CODE_INVALID_TOKEN);
        }
        $this->data = $info;
        $this->status = true;
    }

    /**
     * 获取令牌信息
     * @param $key
     * @return mixed
     */
    public function get($key = null)
    {
        return is_null($key) ? $this->data : ($this->data[$key] ?? null);
    }

    /**
     * 删除令牌
     * @param null $token
     * @return mixed
     */
    public function del($token = null)
    {
        if (!empty($token)) {
            return $this->redis->del($token);
        }
        if (!empty($this->token)) {
            return $this->redis->del($this->token);
        }
    }
}