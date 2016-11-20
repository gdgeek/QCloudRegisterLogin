<?php
/**
 * Created by PhpStorm.
 * User: ruidi
 * Date: 16/11/19
 * Time: 下午10:08
 */



namespace Common;

class Login{
    function action(){

        echo 1;

        $pdo = new \Config\DbPdo();
        return;
        $ret = new \Common\Result();

        if(!isset($_REQUEST['id'])){
            $ret->error("no id");
            $ret->output();
            return;
        }

        if(!isset($_REQUEST['password'])){

            $ret->error("no password");
            $ret->output();
            return;

        }

        $id = $_REQUEST['id'];
        $password = $_REQUEST['password'];

        $sql = "SELECT password,nickname FROM user WHERE id = :id ";

        $stm = $pdo->db->prepare($sql);

        $stm->bindParam(":id", $id);
        $stm->execute();
        $query = $stm->fetch();

        if($query){
            if($query['password'] != md5($password)){
                $ret->error("wrong password");
                $ret->output();
                return;
            }

            $nickname = $query['nickname'];
            $user = new \stdClass();
            $user->id = $id;
            $user->password = $password;
            $user->nickname = $nickname;
            $ret->user = $user;
        }else{
            $ret->error("no user");
            $ret->output();
            return;

        }

        //$ret->ee();
        $ret->output();

    }

}