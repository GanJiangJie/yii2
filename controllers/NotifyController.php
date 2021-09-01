<?php

namespace app\controllers;

use app\components\WebController;
use yii\base\Exception;

class NotifyController extends WebController
{
    /**
     * 关闭CSRF验证
     * @var bool $enableCsrfValidation
     */
    public $enableCsrfValidation = false;

    /**
     * 订阅监听主题执行
     */
    public function actionListen()
    {
        $post_json = file_get_contents('php://input');
        $post = json_decode($post_json, true);
        $message_json = $post['Message'];
        $message = json_decode($message_json, true);
        $message_body = $message['messageBody'];
        $receipt_handle = $message['receiptHandle'];

        logPrint()->prefix('listen_')->filename('async');

        $listen_instance = unserialize($message_body);
        $listen_class = get_class($listen_instance);
        try {
            db()->isActive or db()->open();

            $res = listenHandle($listen_instance);
            isset($res['msg']) and logPrint()->writeLog([
                'message_body' => $message_body,
                'listen_instance' => $listen_instance,
                'listen_class' => $listen_class,
                'handle_result' => $res
            ]);
            $result = queue()->deleteMessage(params('mns.queue.listen'), $receipt_handle);
            $result['status'] or throwE($result['response']['error_msg']);
        } catch (Exception $e) {
            logPrint()->writeLog([
                'message_body' => $message_body,
                'listen_instance' => $listen_instance,
                'listen_class' => $listen_class,
                'handle_error' => $e->getMessage()
            ], 2);
        }
    }
}