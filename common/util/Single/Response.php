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
     * @param $data
     * @return $this
     */
    public function data($data)
    {
        if (!is_null($data)) $this->response['data'] = $data;
        return $this;
    }

    /**
     * @param $errMsg
     * @param $errCode
     * @return $this
     */
    public function error($errMsg = null, $errCode = null)
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
    public function getResponse()
    {
        return $this->response;
    }

    public function responseJson()
    {
        header('Content-type:text/html;charset=utf-8');
        return Json::encode($this->response);
    }
}