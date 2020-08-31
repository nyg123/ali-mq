##  阿里云RocketMQ

#### 1,发布普通消息

```php
$config = [
        'http_endpoint' => 'http://101840946123456.mqrest.cn-qingdao.aliyuncs.com', //HTTP接入域名
        'access_key' => 'LTAI4GG1oUUfffwettqmy2Z2', //AccessKey 阿里云身份验证，在阿里云服务器管理控制台创建
        'secret_key' => '6jYo9yuEtertertdfgre8rR432xCl3Vl',  // SecretKey 阿里云身份验证，在阿里云服务器管理控制台创建
        'topic' => 'test_topic', //主题
        'instance_id' => 'MQ_INST_1018409462019097_BXReEF0c', //实例ID
    ];
$data=['a' => 1, 'b' => 2]; //需要发布的数据
$tag='abc'; //消息标签，二级消息类型，用来进一步区分某个 Topic 下的消息分类

try {
    MQ::producer($this->config)->push($data, 'abc'); //推送数据，成功返回MessagePropertiesForPublish类
} catch (Exception $e) {
    $this->assertTrue(false, $e->getMessage());
}
```

#### 2，订阅普通消息
```php
$config = [
        'http_endpoint' => 'http://101840946123456.mqrest.cn-qingdao.aliyuncs.com', //HTTP接入域名
        'access_key' => 'LTAI4GG1oUUfffwettqmy2Z2', //AccessKey 阿里云身份验证，在阿里云服务器管理控制台创建
        'secret_key' => '6jYo9yuEtertertdfgre8rR432xCl3Vl',  // SecretKey 阿里云身份验证，在阿里云服务器管理控制台创建
        'topic' => 'test_topic', //主题
        'instance_id' => 'MQ_INST_1018409462019097_BXReEF0c', //实例ID
    ];
$tag = 'abc';
$consumer=MQ::consumer($config,$tag);
while (true){
$result = $consumer->run(3,10); //设置一次最多消费3条，最多等待10秒
if(empty($result)){ //10秒内没有数据，继续运行
    continue;
}
$this->assertNotEmpty($result);
    foreach ($result as $v) {
        $data =json_decode($v->getMessageBody(),true);
        //在这里处理数据，处理成功后需要调用ask
        $ask=$consumer->ask($v->getReceiptHandle());
 
    }
}
```