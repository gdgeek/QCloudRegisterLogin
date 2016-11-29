<?php
/**
 * Created by PhpStorm.
 * User: ruidi
 * Date: 16/11/18
 * Time: 下午7:31
 */

//$webserver = new ('http://0.0.0.0:8080');

namespace Common;//命名空间
class DbPdo{
    public $db;
    function __construct() {
        //下面请替换为你的MySQL服务器信息
        $server = "583133f034b53.gz.cdb.myqcloud.com";
        $port = "15569";
        $user = "cdb_outerroot";
        $pwd = "qs^%$#@!";
        $db = "qgame";
        try{
            $this->db = new \PDO("mysql:host=$server;port=$port;dbname=$db", $user, $pwd);//创建PDO对象
            $this->db->query("set character set 'utf8'");//设置字符集
        }catch (PDOException $e){
              echo $this->db . "<br>" . $e->getMessage();//如果失败输出异常信息
        }
    }

};