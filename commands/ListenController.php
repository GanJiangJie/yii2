<?php

namespace app\commands;

use app\components\ConsoleController;
use yii\base\Exception;

class ListenController extends ConsoleController
{
    //异步轮询执行监听
    public function actionAsync()
    {
        $log = logPrint()->prefix('listen_')->prefix('async');
        while (true) {
            $re = queue()->receiveMessage(params('mns.queue.listen'));
            if (!$re['status']) {
                app()->db->close();
                continue;
            }

            $message_body = unserialize($re['response']['messageBody']);//监听对象
            $receipt_handle = $re['response']['receiptHandle'];

            $listen_class = get_class($message_body);
            try {
                app()->db->open();

                $res = listenHandle($message_body);
                if (isset($res['msg'])) {
                    $log->writeLog([
                        'class' => $listen_class,
                        'input' => $message_body,
                        'output' => $res
                    ], 3);
                }
                $result = queue()->deleteMessage(params('mns.queue.listen'), $receipt_handle);
                if (!$result['status']) {
                    throw new Exception($result['response']['error_msg']);
                }
            } catch (Exception $e) {
                $log->writeLog([
                    'class' => get_class($message_body),
                    'input' => $message_body,
                    'error' => $e->getMessage()
                ], 3);
            }
        }
    }
}