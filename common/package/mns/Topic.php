<?php

namespace app\common\package\mns;

use AliyunMNS\Model\SubscriptionAttributes;
use AliyunMNS\Requests\PublishMessageRequest;
use AliyunMNS\Requests\CreateTopicRequest;
use AliyunMNS\Exception\MnsException;
use app\common\traits\MnsTrait;

class Topic
{
    use MnsTrait;

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