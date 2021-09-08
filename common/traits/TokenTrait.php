<?php

namespace app\common\traits;

use app\common\constant\Constant as C;
use common\util\DataCheck\Validator;
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
     */
    public function del()
    {
        $token = requestParams($this->name);
        if (empty($token)) return false;
        return redis(RedisK::class, 'Del', [$this->prefix . $token], $this->driver);
    }

    /**
     * 生成令牌
     * @param $data_json
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
        empty($token) and tbe(C::__API_ERROR_CODE[C::API_ERROR_CODE_LACK_TOKEN], C::API_ERROR_CODE_LACK_TOKEN);
        $info_json = redis(RedisS::class, 'Get', [$this->prefix . $token], $this->driver);
        empty($info_json) || !Validator::isJson($info_json) and
        tbe(C::__API_ERROR_CODE[C::API_ERROR_CODE_INVALID_TOKEN], C::API_ERROR_CODE_INVALID_TOKEN);
        $this->data = $info_json;
    }
}