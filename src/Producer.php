<?php

namespace Nyg;

use Exception;
use MQ\MQProducer;
use MQ\Model\TopicMessage;
use MQ\Traits\MessagePropertiesForPublish;

class Producer
{

    /**
     * @var MQProducer 生产者
     */
    private $producer;

    public function __construct(MQProducer $producer)
    {
        $this->producer = $producer;
    }

    /**
     * 推送
     * @param array $data 推送数据，数组
     * @param string $tag 标签
     * @param string $key 键值
     * @return MessagePropertiesForPublish
     * @throws Exception
     * @author 牛永光 nyg1991@aliyun.com
     * @date 2020/8/26 21:33
     */
    public function push(array $data, $tag = '', $key = '')
    {
        $message = new TopicMessage(json_encode($data));
        if ($tag) {
            $message->setMessageTag($tag);
        }
        if ($key) {
            $message->setMessageKey($key);
        }
        try {
            return $this->producer->publishMessage($message);
        } catch (Exception $e) {
            throw new $e;
        }
    }
}