<?php
/**
 * Created by PhpStorm.
 * User: ruidi
 * Date: 16/11/18
 * Time: 下午7:31
 */

//$webserver = new ('http://0.0.0.0:8080');

namespace Common;

class DbPdo{

    public $db;

    function __construct() {
       // $server = "localhost:8889";

        $server = "583133f034b53.gz.cdb.myqcloud.com";
        $port = "15569";
        $user = "cdb_outerroot";
        $pwd = "qs^%$#@!";
        $db = "qgame";
        try{
            $this->db = new \PDO("mysql:host=$server;port=$port;dbname=$db", $user, $pwd);
            $this->db->query("set character set 'utf8'");
        }catch (PDOException $e){
              echo $this->db . "<br>" . $e->getMessage();
        }
    }

};
