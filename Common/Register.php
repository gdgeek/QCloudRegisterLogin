<?php
/**
 * Created by PhpStorm.
 * User: ruidi
 * Date: 16/11/19
 * Time: 上午10:26
 */


namespace Common;

class Register{
    function make_password( $length = 20 ) {
        // 密码字符集，可任意添加你需要的字符
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        $password = '';
        for ( $i = 0; $i < $length; $i++ )
        {
            $password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
        }

        return $password;
    }

    function action(){
        $ret = new \Common\Result();
        if(isset($_REQUEST['nickname'])){

            $pdo = new \Config\DbPdo();
            $user = new \stdClass();
            $user->nickname = $_REQUEST['nickname'];
            $user->password = $this->make_password();

            $sql = "SELECT COUNT(*) FROM user WHERE nickname = :nickname";
            $stm = $pdo->db->prepare($sql);

            $stm->bindParam(":nickname", $user->nickname);
            $stm->execute();
            $query = $stm->fetch();

            if($query['COUNT(*)'] == 0){
                $sql = "INSERT INTO `user` (`nickname`,`password`)VALUES (:nickname, :password)";
                $stm = $pdo->db->prepare($sql);
                $stm->bindParam(":nickname", $user->nickname);
                $stm->bindParam(":password", md5($user->password));
                $stm->execute();
                $user->id = $pdo->db->lastInsertId();
                $ret->user = $user;

            }else{
                $ret->error("repeat nickname");
                $ret->output();

                return;
            }

        }else{
            $ret->error("no nickname");
            $ret->output();
            return;

        }

        $ret->output();

    }
};
