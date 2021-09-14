<?php

namespace app\common\util\Single;

use app\common\constant\Constant as C;
use app\common\traits\InstanceTrait;
use yii\helpers\Json;

class Response
{
    use InstanceTrait;

    /**
     * @var array $response
     */
    public $response;

    /**
     * Response constructor.
     */
    public function __construct()
    {
        $this->response = [
            'errCode' => C::API_ERROR_CODE_SUCCESS,
            'errMsg' => C::__API_ERROR_CODE[C::API_ERROR_CODE_SUCCESS]
        ];
    }

    /**
     * @param array $data
     * @return Response
     */
    public function data(array $data = null): self
    {
        $data and $this->response['data'] = $data;
        return $this;
    }

    /**
     * @param string $errMsg
     * @param int $errCode
     * @return $this
     */
    public function error(string $errMsg = '', int $errCode = 0): self
    {
        $this->response = [
            'errCode' => $errCode ?: C::API_ERROR_CODE_FAIL,
            'errMsg' => $errMsg ?: C::__API_ERROR_CODE[C::API_ERROR_CODE_FAIL]
        ];
        return $this;
    }

    /**
     * @return string
     */
    public function responseJson(): string
    {
        header('Content-type:text/html;charset=utf-8');
        return Json::encode($this->response);
    }
}