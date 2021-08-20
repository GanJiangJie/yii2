<?php

namespace app\controllers;

use Yii;
use yii\base\Exception;
use app\common\util\Route;
use app\components\WebController;
use common\util\DataCheck\DataCheck;
use common\util\DataCheck\DataCheckBase;

class OpenController extends WebController
{
    public function actionIndex()
    {
        //获取请求参数
        $params = request()->params();
        $response = response();
        $log = logPrint()->category('api_log')->prefix('api_success_');
        try {
            //验证必填参数
            DataCheckBase::checkValidEmpty($params, ['app_id', 'method', 'sign_type', 'version', 'sign']);
            //验证签名类型
            DataCheckBase::checkSignType($params['sign_type']);
            //验证版本
            DataCheckBase::checkVersion($params['version']);
            //验证签名
            DataCheck::checkSign($params);
            //方法获取路由
            $route = Route::method($params['method']);
            //跳转路由
            $data = Yii::$app->runAction($route);
            //响应结果处理
            $response->data($data);
        } catch (Exception $e) {
            //抛出异常处理
            $response->err($e->getCode(), $e->getMessage());
            $log->prefix('api_fail_');
        } finally {
            //打印日志
            $log->writeLog(['request' => $params, 'response' => $response->response()], 3);
            //输出响应
            $response->responseJsonExit();
        }
    }
}