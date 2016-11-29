<?php
require_once '../cmq/cmq_api.php';
require_once CMQAPI_ROOT_PATH . '/account.php';
require_once CMQAPI_ROOT_PATH . '/queue.php';
require_once CMQAPI_ROOT_PATH . '/cmq_exception.php';

class CreateQueueSendMessage
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

        $queue_meta = new QueueMeta();
        $queue_meta->queueName = $queue_name;
        $queue_meta->pollingWaitSeconds = 10;
        $queue_meta->visibilityTimeout = 10;
        $queue_meta->maxMsgSize = 1024;
        $queue_meta->msgRetentionSeconds = 3600;

        // 1. create queue
        try
        {
            $my_queue->create($queue_meta);
            echo "Create Queue Succeed! \n" . $queue_meta . "\n";
        }
        catch (CMQExceptionBase $e)
        {
            echo "Create Queue Fail! Exception: " . $e;
            return;
        }

        // 2. send message
        $msg_body = "I am test message.";
        $msg = new Message($msg_body);
        try
        {
            $re_msg = $my_queue->send_message($msg);
            echo "Send Message Succeed! MessageBody:" . $msg_body . " MessageID:" . $re_msg->msgId . "\n";
        }
        catch (CMQExceptionBase $e)
        {
            echo "Send Message Fail! Exception: " . $e;
            return;
        }

        // 3. receive message
        $receiptHandle = NULL;
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

        // 4. delete message
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

        // 5. delete queue
        try {
            $my_queue->delete();
            echo "Delete Queue Succeed! QueueName:" . $queue_name . "\n";
        } catch (CMQExceptionBase $e) {
            echo "Delete Queue Fail! Exception: " . $e;
            return;
        }
    }
}

$secretId = "您的secretId";
$secretKey = "您的secretKey";
$endPoint = "http://cmq-queue-gz.api.tencentyun.com";

$instance = new CreateQueueSendMessage($secretId, $secretKey, $endPoint);
$instance->run();
