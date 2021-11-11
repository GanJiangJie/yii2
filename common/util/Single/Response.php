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
    public $response;

    /**
     * Response constructor.
     */
    private function __construct()
    {
        $this->response = [
            'errCode' => API_ERROR_CODE_SUCCESS,
            'errMsg' => $GLOBALS['__API_ERROR_CODE'][API_ERROR_CODE_SUCCESS]
        ];
    }

    /**
     * @param array|null $data
     * @return $this
     */
    public function data(array $data = null)
    {
        is_null($data) or $this->response['data'] = $data;
        return $this;
    }

    /**
     * @param string $errMsg
     * @param int $errCode
     * @return $this
     */
    public function error(string $errMsg = '', int $errCode = 0)
    {
        $this->response = [
            'errCode' => $errCode ?: API_ERROR_CODE_FAIL,
            'errMsg' => $errMsg ?: $GLOBALS['__API_ERROR_CODE'][API_ERROR_CODE_FAIL]
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