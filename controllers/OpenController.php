<?php

namespace app\controllers;

use app\components\Exception;
use app\components\WebController;

class OpenController extends WebController
{
    public function actionIndex(): string
    {
        try {
            logPrint()->category('api_log')->prefix('api_');
            route()->handle();
        } catch (Exception $e) {
            //异常处理
            response()->error($e->getMessage(), $e->getCode());
            logPrint()->level(2)->backtrace($e->getFileLine());
        } finally {
            //打印日志
            logPrint()->writeLog([
                'request' => request()->params,
                'response' => response()->response,
                'runtime' => route()->runtime
            ]);
            //输出响应
            return response()->responseJson();
        }
    }
}