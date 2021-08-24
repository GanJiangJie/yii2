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