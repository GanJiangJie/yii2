<?php

namespace app\common\traits;

use AliyunMNS\Client;

trait MnsTrait
{
    use InstanceTrait;

    /**
     * @var string $accessId
     */
    private $accessId;

    /**
     * @var string $accessKey
     */
    private $accessKey;

    /**
     * @var string $endPoint
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
        $this->endPoint = params('mns.ini.endPoint');
        $this->accessId = params('mns.ini.accessId');
        $this->accessKey = params('mns.ini.accessKey');
        $this->client = new Client($this->endPoint, $this->accessId, $this->accessKey);
    }
}