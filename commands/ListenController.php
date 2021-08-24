<?php

namespace app\commands;

use app\components\ConsoleController;
use yii\base\Exception;

class ListenController extends ConsoleController
{
    /**
     * 异步轮询执行监听
     */
    public function actionAsync()
    {
        $log = logPrint()->prefix('listen_')->prefix('async');
        while (true) {
            $re = queue()->receiveMessage(params('mns.queue.listen'));
            if (!$re['status']) {
                app()->db->close();
                continue;
            }

            $message_body = $re['response']['messageBody'];
            $receipt_handle = $re['response']['receiptHandle'];

            $listen_instance = unserialize($message_body);
            $listen_class = get_class($listen_instance);
            try {
                app()->db->open();

                //1直接执行
                /*$res = listenHandle($listen_instance);
                if (isset($res['msg'])) {
                    $log->writeLog([
                        'message_body' => $message_body,
                        'listen_instance' => $listen_instance,
                        'listen_class' => $listen_class,
                        'handle_result' => $res
                    ], 3);
                }
                $result = queue()->deleteMessage(params('mns.queue.listen'), $receipt_handle);
                if (!$result['status']) {
                    throw new Exception($result['response']['error_msg']);
                }*/

                //2发布主题
                $res = topic()->PublishMessage(params('mns.topic.listen'), json_encode([[
                    'receiptHandle' => $receipt_handle,
                    'messageBody' => $message_body
                ]], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
                if (!$res['status']) {
                    throw new Exception($res['msg']);
                }
            } catch (Exception $e) {
                $log->writeLog([
                    'message_body' => $message_body,
                    'listen_instance' => $listen_instance,
                    'listen_class' => $listen_class,
                    'handle_error' => $e->getMessage()
                ], 3);
            }
        }
    }
}