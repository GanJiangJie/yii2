<?php

namespace app\controllers;

use yii\base\Exception;
use app\common\util\Route;
use app\components\WebController;
use common\util\DataCheck\DataCheck;
use common\util\DataCheck\Validator;

class OpenController extends WebController
{
    public function actionIndex(): string
    {
        logPrint()->category('api_log')->prefix('api_');
        try {
            //验证必填参数
            Validator::checkValidEmpty(request()->param(), ['app_id', 'method', 'sign_type', 'version', 'sign']);
            //验证签名类型
            DataCheck::checkSignType(request()->param('sign_type'));
            //验证版本
            DataCheck::checkVersion(request()->param('version'));
            //验证签名
            DataCheck::checkSign(request()->param());
            //方法获取路由
            //$route = Route::method(request()->param('method'));
            //跳转路由
            //$data = app()->runAction($route);
            //响应结果处理
            //response()->data($data);
            response()->data(app()->runAction(Route::method(request()->param('method'))));
        } catch (Exception $exception) {
            //抛出异常处理
            response()->error($exception->getMessage(), $exception->getCode());
            logPrint()->level(2);
        } finally {
            //打印日志
            logPrint()->writeLog(['request' => request()->param(), 'response' => response()->getResponse()]);
            //输出响应
            return response()->responseJson();
        }
    }
}