<?php

namespace app\common\traits;

use AliyunMNS\Client;

trait MnsTrait
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

    private $mns = 'mns.config.';

    /**
     * Queue constructor.
     */
    private function __construct()
    {
        $this->endPoint = params($this->mns . 'endPoint');
        $this->accessId = params($this->mns . 'accessId');
        $this->accessKey = params($this->mns . 'accessKey');
        $this->client = new Client($this->endPoint, $this->accessId, $this->accessKey);
    }
}