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
        $ini_array = parse_ini_file(BASE_PATH . '/common/package/mns/config.ini');
        $this->endPoint = $ini_array['endPoint'];
        $this->accessId = $ini_array['accessId'];
        $this->accessKey = $ini_array['accessKey'];
        $this->client = new Client($this->endPoint, $this->accessId, $this->accessKey);
    }
}