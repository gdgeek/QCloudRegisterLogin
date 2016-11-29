<?php
require_once '../cmq/cmq_api.php';
require_once CMQAPI_ROOT_PATH . '/account.php';
require_once CMQAPI_ROOT_PATH . '/queue.php';
require_once CMQAPI_ROOT_PATH . '/cmq_exception.php';

class ReceiveMessage
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

        $receiptHandle = NULL;
        while (TRUE) {
            try
            {
                $recv_msg = $my_queue->receive_message(3);
                echo "Receive Message Succeed! " . $recv_msg . "\n";
                $receiptHandle = $recv_msg->receiptHandle;
            }
            catch (CMQExceptionBase $e)
            {
                echo "Receive Message Fail! Exception: " . $e;
                return;
            }

            try
            {
                $my_queue->delete_message($receiptHandle);
                echo "Delete Message Succeed!  ReceiptHandle:" . $receiptHandle . "\n";
            }
            catch (CMQExceptionBase $e)
            {
                echo "Delete Message Fail! Exception: " . $e;
                return;
            }
        }
    }
}

$secretId = "您的secretId";
$secretKey = "您的secretKey";
$endPoint = "http://cmq-queue-gz.api.tencentyun.com";

$instance = new ReceiveMessage($secretId, $secretKey, $endPoint);
$instance->run();
