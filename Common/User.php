<?php
/**
 * Created by PhpStorm.
 * User: ruidi
 * Date: 16/11/21
 * Time: 下午3:25
 */

namespace Common;


class User
{
    public $ret;
    public $data;

    function __construct($pdo) {
        $this->ret = new \Common\Result();






        if(!isset($_REQUEST['id'])) {
            $this->ret->error('no id');
          //  $this->ret->output();
            return;
        }

        if(!isset($_REQUEST['password'])) {
            $this->ret->error('no password');
           // $this->ret->output();
            return;
        }

       // $this->pdo = ;
        $id = $_REQUEST['id'];
        $password = $_REQUEST['password'];

        $sql = "SELECT password,nickname FROM user WHERE id = :id ";

        $stm = $pdo->db->prepare($sql);

        $stm->bindParam(":id", $id);
        $stm->execute();
        $query = $stm->fetch();

        if($query){
            if($query['password'] != md5($password)){
                $this->ret->error("wrong password");
               // $this->ret->output();
                return;
            }

        }else{
            $this->ret->error("no user");
           // $this->ret->output();
            return;

        }

        $this->data = new \stdClass();
        $this->data->id = $id;
        $nickname = $query['nickname'];
        //$score = $_REQUEST['score'];
        $this->data->nickname = $nickname;
        //$user->score = $score;
        //$this->setScores($user, $score);
        //$ret->top10 = $this->_top10();
       // $ret->output();


    }



}