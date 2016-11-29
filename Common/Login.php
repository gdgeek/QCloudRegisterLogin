<?php
/**
 * Created by PhpStorm.
 * User: ruidi
 * Date: 16/11/19
 * Time: 下午10:08
 */

namespace Common;
class Login{
    //具体操作
    function action(){
    
        //创建返回类型
        $ret = new \Common\Result();
        //如果用户没有提交id,报错
        if(!isset($_REQUEST['id'])){
            $ret->error("no id");
            $ret->output();
            return;
        }
        //如果用户没提交密码,报错
        if(!isset($_REQUEST['password'])){
            $ret->error("no password");
            $ret->output();
            return;

        }
        //创建数据库管理类,
        $pdo = new \Common\DbPdo();
        $id = $_REQUEST['id'];
        $password = $_REQUEST['password'];
        //通过id查询信息
        $sql = "SELECT password,nickname FROM user WHERE id = :id ";

        $stm = $pdo->db->prepare($sql);
        $stm->bindParam(":id", $id);
        $stm->execute();
        $query = $stm->fetch();
        //如果查询成功
        if($query){
            //如果密码不正确，报错
            if($query['password'] != md5($password)){
                $ret->error("wrong password");
                $ret->output();
                return;
            }
            //得到用户信息
            $nickname = $query['nickname'];
            $user = new \stdClass();
            $user->id = $id;
            $user->password = $password;
            $user->nickname = $nickname;
            $ret->user = $user;
        }else{//如果数据库里没有此id,报错
            $ret->error("no user");
            $ret->output();
            return;

        }
        //返回正确结果
        $ret->output();

    }

}