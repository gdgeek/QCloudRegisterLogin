<?php
require_once '../cmq/cmq_api.php';
require_once CMQAPI_ROOT_PATH . '/account.php';
require_once CMQAPI_ROOT_PATH . '/queue.php';
require_once CMQAPI_ROOT_PATH . '/cmq_exception.php';

class BatchSendMessage
{
    private $secretId;
    private $secretKey;
    private $endpoint;

    public function __construct($secretId, $secretKey, $endpoint)
    {
        $this->secretId = $secretId;
        $this->secretKey = $secretKey;
        $this->endpoint = $endpoint;
    }

    public function run()
    {
        $queue_name = "MySampleQueue1";
        $my_account = new Account($this->endpoint, $this->secretId, $this->secretKey);

        $my_queue = $my_account->get_queue($queue_name);

        $messages = array();
        for ($i=0; $i<3; $i++) {
            $msg_body = "I am test message " . $i;
            $msg = new Message($msg_body);
            $messages [] = $msg;
        }
        try
        {
            $re_msg_list = $my_queue->batch_send_message($messages);
            echo "Batch Send Message Succeed! " . json_encode($re_msg_list);
        }
        catch (CMQExceptionBase $e)
        {
            echo "Batch Send Message Fail! Exception: " . $e;
            return;
        }
    }
}

$secretId = "您的secretId";
$secretKey = "您的secretKey";
$endPoint = "http://cmq-queue-gz.api.tencentyun.com";

$instance = new BatchSendMessage($secretId, $secretKey, $endPoint);
$instance->run();
