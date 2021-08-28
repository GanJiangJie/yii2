<?php

namespace app\common\package\mns;

use AliyunMNS\Exception\MnsException;
use AliyunMNS\Requests\SendMessageRequest;
use app\common\traits\MnsTrait;

class Queue
{
    use MnsTrait;

    /**
     * 发送消息
     * @param string $queue_name
     * @param string $message
     * @return array
     */
    public function sendMessage(string $queue_name, string $message): array
    {
        $queue = $this->client->getQueueRef($queue_name);
        $request = new SendMessageRequest($message);
        try {
            $res = $queue->sendMessage($request);
            return [
                'status' => true,
                'response' => [
                    'statusCode' => $res->getStatusCode(),
                    'messageId' => $res->getMessageId()
                ]
            ];
        } catch (MnsException $e) {
            return [
                'status' => false,
                'response' => [
                    'error_code' => $e->getMnsErrorCode(),
                    'error_msg' => $e->getMessage()
                ]
            ];
        }
    }

    /**
     * 消费消息
     * @param string $queue_name
     * @return array
     */
    public function receiveMessage(string $queue_name): array
    {
        $queue = $this->client->getQueueRef($queue_name);
        try {
            $res = $queue->receiveMessage();
            return [
                'status' => true,
                'response' => [
                    'statusCode' => $res->getStatusCode(),
                    'receiptHandle' => $res->getReceiptHandle(),
                    'messageBody' => $res->getMessageBody(),
                    'messageId' => $res->getMessageId()
                ]
            ];
        } catch (MnsException $e) {
            return [
                'status' => false,
                'response' => [
                    'error_code' => $e->getMnsErrorCode(),
                    'error_msg' => $e->getMessage()
                ]
            ];
        }
    }

    /**
     * 删除消息
     * @param string $queue_name
     * @param string $receipt_handle
     * @return array
     */
    public function deleteMessage(string $queue_name, string $receipt_handle): array
    {
        $queue = $this->client->getQueueRef($queue_name);
        try {
            $res = $queue->deleteMessage($receipt_handle);
            return [
                'status' => true,
                'response' => [
                    'statusCode' => $res->getStatusCode()
                ]
            ];
        } catch (MnsException $e) {
            return [
                'status' => false,
                'response' => [
                    'error_code' => $e->getMnsErrorCode(),
                    'error_msg' => $e->getMessage()
                ]
            ];
        }
    }
}