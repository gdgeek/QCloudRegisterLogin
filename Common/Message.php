<?php
/**
 * Created by PhpStorm.
 * User: ruidi
 * Date: 16/11/29
 * Time: 上午10:06
 */


namespace Common;//命名空间


require_once '../cmq/cmq_api.php';

require_once CMQAPI_ROOT_PATH . '/account.php';
require_once CMQAPI_ROOT_PATH . '/queue.php';
require_once CMQAPI_ROOT_PATH . '/cmq_exception.php';



class Message{

    private $secretId;
    private $secretKey;
    private $endpoint;

    public function __construct(/*$secretId, $secretKey, $endpoint*/)
    {

        $this->secretId = "AKIDb5OWSF6Gj074rRT590e6MPQK4fS5nmqm";
        $this->secretKey = "rWzFI9nJckgS1TjokV4RbYO2ThkfQDrK";
        $this->endpoint = "http://cmq-queue-gz.api.qcloud.com";
       // $this->secretId = $secretId;
        //$this->secretKey = $secretKey;
       // $this->endpoint = $endpoint;
    }
    private function getQueue($id, $create = true){
        $queue_name = "Queue-".$id;
       // echo $queue_name;
        $my_account = new \Account($this->endpoint, $this->secretId, $this->secretKey);
        $my_queue = $my_account->get_queue($queue_name);

        try{
           $attributes =  $my_queue->get_attributes();

        } catch (\CMQExceptionBase $e)
        {
            if($create){
                $queue_meta = new \QueueMeta();
                $queue_meta->queueName = $queue_name;
                $queue_meta->pollingWaitSeconds = 10;
                $queue_meta->visibilityTimeout = 10;
                $queue_meta->maxMsgSize = 1024;
                $queue_meta->msgRetentionSeconds = 1600;
                $my_queue->create($queue_meta);
                return $my_queue;
            }
            return;
        }
        return $my_queue;
    }
    public function send($id, $pack){
        $queue = $this->getQueue($id);
        $msg = new \Message(json_encode($pack));

        $re_msg = $queue->send_message($msg);

        return $re_msg;


    }
    public function receive($id){

        $queue = $this->getQueue($id);

        $wait_seconds = 1;
        $num_of_msg = 10;


         $recv_msg_list = $queue->batch_receive_message($num_of_msg, $wait_seconds);

        $receiptHandles = array();
        $ret = array();
        foreach ($recv_msg_list as $key => $value) {

            array_push($ret, json_decode($value->msgBody));

            $receiptHandles [] = $value->receiptHandle;
        }


        $queue->batch_delete_message($receiptHandles);

        return $ret;

    }
    public function send_action(){

        if(!isset($_REQUEST['register'])){

            $ret = new \Common\Result();
            $ret->error("no set register");
            $ret->output();
            return;
        }else if(!isset($_REQUEST['message'])){
            $ret = new \Common\Result();
            $ret->error("no message");
            $ret->output();
            return;
        }
        $id = $_REQUEST['register'];

        $message = $_REQUEST['message'];
        $pdo = new \Common\DbPdo();

        $sql = "SELECT count(*) FROM user WHERE id = :id ";
        $stm = $pdo->db->prepare($sql);

        $stm->bindParam(":id", $id);
        $stm->execute();
        $query = $stm->fetch();
        if($query['count(*)'] == 0){
            $ret = new \Common\Result();
            $ret->error("no register");
            $ret->output();
            return;
        }

        $user = new \Common\User($pdo);
        $ret = $user->ret;
        if(!$ret->succeed){
            $ret->output();
            return;
        }




        $pack = new \stdClass();
        $pack->sender = $user->data;
        $pack->receiveID = $id;
        $pack->message = $message;
        $this->send($id, $pack);
        $ret->output();
        //$pack->
    }

    public function receive_action(){
        $pdo = new \Common\DbPdo();
        $user = new \Common\User($pdo);
        $ret = $user->ret;
        if(!$ret->succeed){
            $ret->output();
            return;
        }
        try
        {

            $ret->receive = $this->receive($user->data->id);
        }catch (\CMQExceptionBase $e)
        {


            $ret->error($e->getMessage());
            $ret->output();
            return;
        }
        $ret->output();
    }



}