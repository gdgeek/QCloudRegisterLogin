<?php
/**
 * Created by PhpStorm.
 * User: ruidi
 * Date: 16/11/18
 * Time: 下午7:31
 */

//$webserver = new ('http://0.0.0.0:8080');

namespace Config;

class DbPdo{

    public $db;

    function __construct() {
        $server = "localhost:8889";
        $user = "web";
        $pwd = "Ot6QzOCnYEhQl4Y2";
        $db = "qgame";
        try{
            $this->db = new \PDO("mysql:host=$server;dbname=$db", $user, $pwd);
            $this->db->query("set character set 'utf8'");
        }catch (PDOException $e){
              echo $this->db . "<br>" . $e->getMessage();
        }
    }

};
