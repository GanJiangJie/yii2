<?php

namespace app\controllers;

use yii\base\Exception;
use app\components\WebController;
use common\util\DataCheck\DataCheck;
use common\util\DataCheck\Validator;

class OpenController extends WebController
{
    public function actionIndex(): string
    {
        try {
            logPrint()->category('api_log')->prefix('api_');//api日志

            self::dataCheck();

            route()->method(requestParams('method'));//method获取route

            route()->beforeHandle();//front处理

            response()->data(app()->runAction(route()->route));//响应结果

            route()->afterHandle();//behind处理
        } catch (Exception $e) {
            //抛出异常处理
            response()->error($e->getMessage(), $e->getCode());
            logPrint()->level(2)->backtrace(exception());
        } finally {
            //打印日志
            logPrint()->writeLog(['request' => request()->params, 'response' => response()->response]);
            //输出响应
            return response()->responseJson();
        }
    }

    /**
     * @throws Exception
     */
    private function dataCheck()
    {
        Validator::notEmpty(request()->params, ['app_id', 'method', 'sign_type', 'version', 'sign']);//验证必填参数

        DataCheck::signType(requestParams('sign_type'));//验证签名类型

        DataCheck::version(requestParams('version'));//验证版本

        DataCheck::checkSign(request()->params);//验证签名
    }
}