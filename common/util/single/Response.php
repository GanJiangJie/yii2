<?php

namespace app\common\util\single;

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
     * @return array
     */
    public function response()
    {
        return $this->response;
    }

    /**
     * @param $data
     * @return $this
     */
    public function data($data)
    {
        if (!is_null($data)) $this->response['data'] = $data;
        return $this;
    }

    /**
     * @param string $errCode
     * @param string $errMsg
     * @return $this
     */
    public function error($errCode, $errMsg)
    {
        $this->response = [
            'errCode' => $errCode ?: API_ERROR_CODE_FAIL,
            'errMsg' => $errMsg ?: $GLOBALS['__API_ERROR_CODE'][API_ERROR_CODE_FAIL]
        ];
        return $this;
    }

    public function responseJsonExit()
    {
        header('Content-type:text/html;charset=utf-8');
        echo Json::encode($this->response);
        exit();
    }
}