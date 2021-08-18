<?php

namespace app\common\package\mns;

use AliyunMNS\Client;
use AliyunMNS\Exception\MnsException;
use AliyunMNS\Requests\SendMessageRequest;
use app\common\traits\InstanceTrait;

class Queue
{
    use InstanceTrait;

    /**
     * @var $accessId
     */
    private $accessId;

    /**
     * @var $accessKey
     */
    private $accessKey;

    /**
     * @var $endPoint
     */
    private $endPoint;

    /**
     * @var Client $client
     */
    private $client;

    /**
     * Queue constructor.
     */
    private function __construct()
    {
        $ini_array = parse_ini_file(__DIR__ . '/config.ini');
        $this->endPoint = $ini_array['endpoint'];
        $this->accessId = $ini_array['accessid'];
        $this->accessKey = $ini_array['accesskey'];
        $this->client = new Client($this->endPoint, $this->accessId, $this->accessKey);
    }

    /**
     * 发送消息
     * @param string $queue_name
     * @param string $message
     * @return array
     */
    public function sendMessage($queue_name, $message)
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
    public function receiveMessage($queue_name)
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
    public function deleteMessage($queue_name, $receipt_handle)
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