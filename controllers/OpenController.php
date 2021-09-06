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
        try {
            //api日志
            logPrint()->category('api_log')->prefix('api_');
            //验证必填参数
            Validator::notEmpty(rParams(), ['app_id', 'method', 'sign_type', 'version', 'sign']);
            //验证签名类型
            DataCheck::signType(rParams('sign_type'));
            //验证版本
            DataCheck::version(rParams('version'));
            //验证签名
            DataCheck::checkSign(rParams());
            //响应结果
            response()->data(app()->runAction(Route::method(rParams('method'))));
        } catch (Exception $e) {
            //抛出异常处理
            response()->error($e->getMessage(), $e->getCode());
            logPrint()->level(2)->backtrace(exception());
        } finally {
            //打印日志
            logPrint()->writeLog(['request' => rParams(), 'response' => response()->getResponse()]);
            //输出响应
            return response()->responseJson();
        }
    }
}