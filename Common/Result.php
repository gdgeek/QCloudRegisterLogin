<?php
/**
 * Created by PhpStorm.
 * User: ruidi
 * Date: 16/11/19
 * Time: 上午10:26
 */


namespace Common;

class Result{

    public $epoch;
    public $succeed;
    public $message;

    function __construct() {
        $this->epoch = time();
        $this->succeed = true;
        $this->message = "succeed";
    }
    public function output(){
        echo json_encode($this);
    }

    public function error($message){
        $this->message = $message;
        $this->succeed = false;
    }
};
