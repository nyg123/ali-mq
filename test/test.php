<?php

use Nyg\MQ;

include_once './vendor/autoload.php';
include_once './src/MQ.php';

class Test extends \PHPUnit\Framework\TestCase
{
    public $config = [
        'http_endpoint' => 'http://101840946123456.mqrest.cn-qingdao.aliyuncs.com',
        'access_key' => 'LTAI4GG1oUUfffwettqmy2Z2',
        'secret_key' => '6jYo9yuEtertertdfgre8rR432xCl3Vl',
        'topic' => 'test_topic',
        'instance_id' => 'MQ_INST_1018409462019097_BXReEF0c',
    ];

    /**
     * 生产
     * @author 牛永光 nyg1991@aliyun.com
     * @date 2020/8/31 10:38
     */
    public function testProducer()
    {

        $data = ['a' => 1, 'b' => 2];
        try {
           MQ::producer($this->config)->push($data, 'abc',time());
        } catch (Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }

    /**
     * 消费
     * @throws Exception
     * @author 牛永光 nyg1991@aliyun.com
     * @date 2020/8/31 10:39
     */
    public function testConsumer()
    {
        $tag = 'abc';
        $consumer=MQ::consumer($this->config,$tag);
        $result = $consumer->run();
        $this->assertNotEmpty($result);
        foreach ($result as $v) {
            $data =json_decode($v->getMessageBody(),true);
            $this->assertNotEmpty($data);
            $ask=$consumer->ask($v->getReceiptHandle());
            $this->assertTrue($ask);
        }
    }
}