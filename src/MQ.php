<?php

namespace Nyg;

use MQ\MQClient;

class MQ
{
    /**
     * @var array 客户端数组
     */
    private static $client_arr;

    /**
     * @var array 生产者数组
     */
    private static $producer_arr;

    /**
     * @var array 消费者数组
     */
    private static $consumer_arr;

    /**
     * 获取生产者
     * @param $config
     * @return Producer
     * @author 牛永光 nyg1991@aliyun.com
     * @date 2020/8/26 18:50
     */
    public static function producer($config)
    {
        $key = md5(json_encode($config));
        if (isset(self::$producer_arr[$key])) {
            return new Producer(self::$producer_arr[$key]);
        }
        $client = self::client($config['http_endpoint'], $config['access_key'], $config['secret_key']);
        self::$producer_arr[$key] = $client->getProducer($config['instance_id'], $config['topic']);
        return new Producer(self::$producer_arr[$key]);
    }

    /**
     * 返回客户端
     * @param $http_endpoint
     * @param $access_key
     * @param $secret_key
     * @return MQClient
     * @author 牛永光 nyg1991@aliyun.com
     * @date 2020/8/26 18:43
     */
    private static function client($http_endpoint, $access_key, $secret_key)
    {
        $key = md5($http_endpoint . $access_key . $secret_key);
        if (isset(self::$client_arr[$key])) {
            return self::$client_arr[$key];
        }
        self::$client_arr[$key] = new MQClient($http_endpoint, $access_key, $secret_key);
        return self::$client_arr[$key];
    }

    /**
     * 获取消费者
     * @param $config
     * @param null $tag 过滤标签
     * @return Consumer
     * @author 牛永光 nyg1991@aliyun.com
     * @date 2020/8/26 22:15
     */
    public static function consumer($config, $tag = null)
    {
        $key = md5(json_encode($config));
        if (isset(self::$consumer_arr[$key])) {
            return new Consumer(self::$consumer_arr[$key]);
        }
        $client = self::client($config['http_endpoint'], $config['access_key'], $config['secret_key']);
        self::$consumer_arr[$key] = $client->getConsumer($config['instance_id'], $config['topic'], $config['group_id'], $tag);
        return new Consumer(self::$consumer_arr[$key]);
    }
}