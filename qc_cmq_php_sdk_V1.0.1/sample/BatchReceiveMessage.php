<?php
require_once '../cmq/cmq_api.php';
require_once CMQAPI_ROOT_PATH . '/account.php';
require_once CMQAPI_ROOT_PATH . '/queue.php';
require_once CMQAPI_ROOT_PATH . '/cmq_exception.php';

class BatchReceiveMessage
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

        $wait_seconds = 3;
        $num_of_msg = 3;
        while (TRUE) {
            try
            {
                $recv_msg_list = $my_queue->batch_receive_message($num_of_msg, $wait_seconds);
                echo "Batch Receive Message Succeed! " . json_encode($recv_msg_list) . "\n";
            }
            catch (CMQExceptionBase $e)
            {
                echo "Batch Receive Message Fail! Exception: " . $e;
                return;
            }

            $receiptHandles = array();
            foreach ($recv_msg_list as $key => $value) {
                $receiptHandles [] = $value->receiptHandle;
            }

            try
            {
                $my_queue->batch_delete_message($receiptHandles);
                echo "Batch Delete Message Succeed!  ReceiptHandles:" . json_encode($receiptHandles) . "\n";
            }
            catch (CMQExceptionBase $e)
            {
                echo "Batch Delete Message Fail! Exception: " . $e;
                return;
            }
        }
    }
}

$secretId = "您的secretId";
$secretKey = "您的secretKey";
$endPoint = "http://cmq-queue-gz.api.tencentyun.com";

$instance = new BatchReceiveMessage($secretId, $secretKey, $endPoint);
$instance->run();
