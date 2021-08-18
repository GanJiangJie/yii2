<?php

namespace app\common\package\mns;

use AliyunMNS\Client;
use AliyunMNS\Model\SubscriptionAttributes;
use AliyunMNS\Requests\PublishMessageRequest;
use AliyunMNS\Requests\CreateTopicRequest;
use AliyunMNS\Exception\MnsException;
use app\common\traits\InstanceTrait;

class Topic
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
     * mns constructor.
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
     * 创建主题
     * @param $topicName
     * @return array
     */
    public function createTopic($topicName)
    {
        $request = new CreateTopicRequest($topicName);
        try {
            $this->client->createTopic($request);
            return [
                'status' => true,
                'msg' => 'ok'
            ];
        } catch (MnsException $e) {
            return [
                'status' => false,
                'msg' => $e->getMnsErrorCode()
            ];
        }
    }

    /**
     * 订阅主题
     * @param $topicName
     * @param $subscriptionName
     * @param $endPoint
     * @return array
     */
    public function Subscribe($topicName, $subscriptionName, $endPoint)
    {
        $topic = $this->client->getTopicRef($topicName);
        $attributes = new SubscriptionAttributes($subscriptionName, $endPoint, NULL, 'JSON');
        try {
            $re = $topic->subscribe($attributes);
            return [
                'status' => true,
                'msg' => 'ok', 'data' => $re
            ];
        } catch (MnsException $e) {
            return [
                'status' => false,
                'msg' => $e->getMnsErrorCode()
            ];
        }
    }

    /**
     * 发布消息
     * @param $topicName
     * @param $messageBody
     * @return array
     */
    public function PublishMessage($topicName, $messageBody)
    {
        $topic = $this->client->getTopicRef($topicName);
        $request = new PublishMessageRequest($messageBody);
        try {
            $re = $topic->publishMessage($request);
            return [
                'status' => true,
                'msg' => 'ok',
                'data' => $re
            ];
        } catch (MnsException $e) {
            return [
                'status' => false,
                'msg' => $e->getMnsErrorCode()
            ];
        }
    }
}