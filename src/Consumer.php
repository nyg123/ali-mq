<?php

namespace Nyg;

use MQ\MQConsumer;
use MQ\Exception\AckMessageException;
use MQ\Exception\MessageNotExistException;
use MQ\Traits\MessagePropertiesForPublish;

class Consumer
{

    /**
     * @var MQConsumer 消费者
     */
    private $consumer;

    public function __construct(MQConsumer $consumer)
    {
        $this->consumer = $consumer;
    }

    /**
     * 监听事件
     * @param int $numOfMessages 一次消费3条(最多可设置为16条)
     * @param int $watiSeconds 长轮询时间3秒（最多可设置为30秒）
     * @return false|\MQ\Model\Message|MessagePropertiesForPublish[]
     * @author 牛永光 nyg1991@aliyun.com
     * @date 2020/8/26 22:04
     */
    public function run($numOfMessages = 3, $watiSeconds = 3)
    {
        try {
            $messages = $this->consumer->consumeMessage($numOfMessages, $watiSeconds);
        } catch (MessageNotExistException $e) {
                return false;
        }
        return $messages;
    }

    /**
     * 应答
     * @param $receipt_handle
     * @return bool
     * @throws \Exception
     * @author 牛永光 nyg1991@aliyun.com+
     * @date 2020/8/26 22:31
     */
    public function ask($receipt_handle)
    {
        if (!is_array($receipt_handle)) {
            $receipt_handle = [$receipt_handle];
        }
        try {
            $this->consumer->ackMessage($receipt_handle);
            return true;
        } catch (\Exception $e) {
            if ($e instanceof AckMessageException) {
                return false;
            }
            throw new $e;
        }
    }
}