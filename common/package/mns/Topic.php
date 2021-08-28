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
     * @param string $topicName
     * @return array
     */
    public function createTopic(string $topicName): array
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
     * @param string $topicName
     * @param string $subscriptionName
     * @param string $endPoint
     * @return array
     */
    public function Subscribe(string $topicName, string $subscriptionName, string $endPoint): array
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
     * @param string $topicName
     * @param string $messageBody
     * @return array
     */
    public function PublishMessage(string $topicName, string $messageBody): array
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