<?php
/**
 * Created by PhpStorm.
 * User: ruidi
 * Date: 16/11/19
 * Time: 上午10:26
 */


namespace Common;//命名空间
class Result{
    public $epoch;//时间戳
    public $succeed;//是否成功
    public $message;//返回消息

    function __construct() {
        //在构造函数里面初始化
        $this->epoch = time();
        $this->succeed = true;
        $this->message = "succeed";
    }
    public function output(){
        //把结果作为json输出
        echo json_encode($this);
    }

    public function error($message){
        //纪录错误
        $this->message = $message;
        $this->succeed = false;
    }
};
