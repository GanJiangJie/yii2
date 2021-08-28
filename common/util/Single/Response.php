<?php

namespace app\common\util\Single;

use app\common\traits\InstanceTrait;
use yii\helpers\Json;

class Response
{
    use InstanceTrait;

    /**
     * @var array $response
     */
    private $response;

    /**
     * Response constructor.
     */
    public function __construct()
    {
        $this->response = [
            'errCode' => API_ERROR_CODE_SUCCESS,
            'errMsg' => $GLOBALS['__API_ERROR_CODE'][API_ERROR_CODE_SUCCESS]
        ];
    }

    /**
     * @param array $data
     * @return Response
     */
    public function data(array $data): self
    {
        $this->response['data'] = $data;
        return $this;
    }

    /**
     * @param string $errMsg
     * @param int $errCode
     * @return $this
     */
    public function error(string $errMsg = null, int $errCode = null): self
    {
        $this->response = [
            'errCode' => $errCode ?: API_ERROR_CODE_FAIL,
            'errMsg' => $errMsg ?: $GLOBALS['__API_ERROR_CODE'][API_ERROR_CODE_FAIL]
        ];
        return $this;
    }

    /**
     * @return array
     */
    public function getResponse(): array
    {
        return $this->response;
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