<?php
require_once '../cmq/cmq_api.php';
require_once CMQAPI_ROOT_PATH . '/account.php';
require_once CMQAPI_ROOT_PATH . '/queue.php';
require_once CMQAPI_ROOT_PATH . '/cmq_exception.php';

class SetQueueAttributes
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
        $queue_meta->pollingWaitSeconds = 5;
        $queue_meta->visibilityTimeout = 10;
        $queue_meta->maxMsgSize = 1024;
        $queue_meta->msgRetentionSeconds = 3600;

        try {
            $my_queue->set_attributes($queue_meta);
            echo "Set Queue Attributes Succeed! QueueMeta:" . $queue_meta . "\n";
        } catch (CMQExceptionBase $e) {
            echo "Set Queue Attributes Fail! Exception: " . $e;
            return;
        }
    }
}

$secretId = "您的secretId";
$secretKey = "您的secretKey";
$endPoint = "http://cmq-queue-gz.api.tencentyun.com";

$instance = new SetQueueAttributes($secretId, $secretKey, $endPoint);
$instance->run();
