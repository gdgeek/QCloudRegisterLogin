<?php
/**
 * Created by PhpStorm.
 * User: ruidi
 * Date: 16/11/19
 * Time: 上午10:26
 */

namespace Common;
class Register{
    // 这个函数用于生成随机密码
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
    //执行函数
    function action(){
        //创建一个返回结果
        $ret = new \Common\Result();
        //检查是否有提供昵称
        if(isset($_REQUEST['nickname'])){
            //创建数据库管理类
            $pdo = new \Common\DbPdo();
            //创建一个用户对象
            $user = new \stdClass();
            //得到数据
            $user->nickname = $_REQUEST['nickname'];
            $user->password = $this->make_password();
            //数据库查询，是否昵称重复
            $sql = "SELECT COUNT(*) FROM user WHERE nickname = :nickname";
            $stm = $pdo->db->prepare($sql);

            $stm->bindParam(":nickname", $user->nickname);
            $stm->execute();
            $query = $stm->fetch();
            //如果昵称唯一
            if($query['COUNT(*)'] == 0){
                //插入用户数据
                $sql = "INSERT INTO `user` (`nickname`,`password`)VALUES (:nickname, :password)";
                $stm = $pdo->db->prepare($sql);
                $stm->bindParam(":nickname", $user->nickname);
                //数据库里面不储存明文密码
                $md5 =  md5($user->password);
                $stm->bindParam(":password",$md5);
                $stm->execute();
                $user->id = $pdo->db->lastInsertId();
                $ret->user = $user;

            }else{//如果数据库里面已经存在相同昵称
                $ret->error("repeat nickname");
                $ret->output();
                //报错
                return;
            }

        }else{//如果用户未提交昵称
            $ret->error("no nickname");
            $ret->output();
            //报错
            return;

        }
        //返回正确结果
        $ret->output();

    }
};
