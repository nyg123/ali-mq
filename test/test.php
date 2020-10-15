<?php

use Nyg\MQ;

include_once './vendor/autoload.php';
include_once './src/MQ.php';

class Test extends \PHPUnit\Framework\TestCase
{
    public $config = [
        'http_endpoint' => 'http://1644908467263130.mqrest.cn-qingdao.aliyuncs.com',
        'access_key' => 'LTAI4GKeyTBc2iFRZsK9iDS5',
        'secret_key' => 'SrQVBZQ2KwnL3FeZgMM7xdWuwXNMI9',
        'topic' => 'test_wt',
        'instance_id' => 'MQ_INST_1644908467263130_BXRlYHoj',
        'group_id'=>'GID_queue_1'
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
            MQ::producer($this->config)->push($data,'abc');
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