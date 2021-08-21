<?php

namespace app\commands;

use app\components\ConsoleController;
use yii\db\Exception;
use Yii;

class ListenController extends ConsoleController
{
    //异步轮询执行监听
    public function actionAsync()
    {
        $log = logPrint()->prefix('listen_')->prefix('async');
        while (true) {
            $re = queue()->receiveMessage(params('mns.queue.listen'));
            if (!$re['status']) {
                Yii::$app->db->close();
                continue;
            }
            try {
                Yii::$app->db->open();
            } catch (Exception $e) {
                $log->writeLog($e->getMessage(), 3);
                continue;
            }
            $message_body = unserialize($re['response']['messageBody']);//监听对象
            $receipt_handle = $re['response']['receiptHandle'];

            $res = listenHandle($message_body);
            if (!$res['status']) {
                $log->writeLog([
                    'class' => get_class($message_body),
                    'input' => $message_body,
                    'output1' => $res
                ], 3);
                continue;
            }
            if (isset($res['msg'])) {
                $log->writeLog([
                    'class' => get_class($message_body),
                    'input' => $message_body,
                    'output2' => $res
                ], 3);
            }
            $result = queue()->deleteMessage(params('mns.queue.listen'), $receipt_handle);
            if (!$result['status']) {
                $log->writeLog([
                    'class' => get_class($message_body),
                    'input' => $message_body,
                    'output3' => $result
                ], 3);
            }
        }
    }
}