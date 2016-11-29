<?php
require_once CMQAPI_ROOT_PATH . '/cmq_client.php';
require_once CMQAPI_ROOT_PATH . '/queue.php';

/*
Account类对象不是线程安全的，如果多线程使用，需要每个线程单独初始化Account类对象
*/
class Account
{
    private $secretId;
    private $secretKey;
    private $cmq_client;

    /*
        @type host: string
        @param host: 访问的url，例如：https://cmq-queue-gz.api.qcloud.com

        @type secretId: string
        @param secretId: 用户的secretId, 腾讯云官网获取

        @type secretKey: string
        @param secretKey: 用户的secretKey，腾讯云官网获取

        @note: Exception
        :: CMQClientParameterException host格式错误
    */
    public function __construct($host, $secretId, $secretKey) {
        $this->host = $host;
        $this->secretId = $secretId;
        $this->secretKey = $secretKey;
        $this->cmq_client = new CMQClient($host, $secretId, $secretKey);
    }

    /* 设置访问的url

        @type host: string
        @param host: 访问的url，例如：http://cmq-queue-gz.api.tencentyun.com

        @type secretId: string
        @param secretId: 用户的secretId，腾讯云官网获取

        @type secretKey: string
        @param secretKey: 用户的secretKey，腾讯云官网获取

        @note: Exception
        :: CMQClientParameterException host格式错误
    */
    public function set_client($host, $secretId=NULL, $secretKey=NULL) {
        if ($secretId == NULL) {
            $secretId = $this->secretId;
        }
        if ($secretKey == NULL) {
            $secretKey = $this->secretKey;
        }
        $this->cmq_client = new CMQClient($host, $secretId, $secretKey);
    }

    /* 获取queue client

        @rtype: CMQClient object
        @return: 返回使用的CMQClient object
    */
    public function get_client() {
        return $this->cmq_client;
    }

    /* 获取Account的一个Queue对象

        @type queue_name: string
        @param queue_name: 队列名

        @rtype: Queue object
        @return: 返回该Account的一个Queue对象
    */
    public function get_queue($queue_name) {
        return new Queue($queue_name, $this->cmq_client);
    }

    /* 列出Account的队列

        @type searchWord: string
        @param searchWord: 队列名的前缀

        @type limit: int
        @param limit: list_queue最多返回的队列数

        @type offset: string
        @param offset: list_queue的起始位置，上次list_queue返回的next_offset

        @rtype: tuple
        @return: QueueURL的列表和下次list queue的起始位置; 如果所有queue都list出来，next_offset为"".
    */
    public function list_queue($searchWord = "", $limit = -1, $offset = "") {
        $params = array();
        if ($searchWord != "") {
            $params['searchWord'] = $searchWord;
        }
        if ($limit != -1) {
            $params['limit'] = $limit;
        }
        if ($offset != "") {
            $params['offset'] = $offset;
        }

        $ret_pkg = $this->cmq_client->list_queue($params);

        if ($offset == "") {
            $next_offset = count($ret_pkg['queueList']);
        }
        else {
            $next_offset = $offset + count($ret_pkg['queueList']);
        }
        if ($next_offset >= $ret_pkg['totalCount']) {
            $next_offset = "";
        }

        return array("totalCount"=>$ret_pkg['totalCount'], "queueList"=>$ret_pkg['queueList'], "next_offset"=>$next_offset);
    }
}
