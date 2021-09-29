<?php

namespace app\common\util\Single;

use app\common\traits\SingleTrait;
use common\util\DataCheck\Validator;
use app\common\util\Redis\{
    RedisK,
    RedisS
};
use yii\base\Exception;

class Token
{
    use SingleTrait;

    private $mKey = 'mToken';

    private $uKey = 'uToken';

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
     * @param string $key
     */
    private function init(string $key)
    {
        $this->driver = config('params.auth.' . $key . '.driver');
        $this->prefix = config('params.auth.' . $key . '.prefix');
        $this->name = config('params.auth.' . $key . '.name');
    }

    /**
     * @return $this
     */
    public function mToken()
    {
        self::init($this->mKey);
        return $this;
    }

    /**
     * @return $this
     */
    public function uToken()
    {
        self::init($this->uKey);
        return $this;
    }

    /**
     * 生成令牌
     * @param string $data_json
     * @return string
     */
    private function token(string $data_json): string
    {
        return md5($data_json . time() . mt_rand(100, 999));
    }

    /**
     * 暂存信息
     * @throws Exception
     */
    private function storage()
    {
        $token = requestParams($this->name);
        empty($token) and tbe('', API_ERROR_CODE_LACK_TOKEN);
        $info_json = redis(RedisS::class, 'Get', [$this->prefix . $token], $this->driver);
        empty($info_json) || !Validator::isJson($info_json) and tbe('', API_ERROR_CODE_INVALID_TOKEN);
        $this->data = $info_json;
    }

    /**
     * 设置令牌
     * @param array $data
     * @return string
     * @throws Exception
     */
    public function set(array $data): string
    {
        $data_json = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $token = self::token($data_json);
        redis(RedisS::class, 'Set', [$this->prefix . $token, $data_json], $this->driver);
        return $token;
    }

    /**
     * 设置令牌，设置期限
     * @param array $data
     * @param int $seconds
     * @return string
     * @throws Exception
     */
    public function setEx(array $data, int $seconds): string
    {
        $data_json = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $token = self::token($data_json);
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