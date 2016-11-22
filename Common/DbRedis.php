<?php
/**
 * Created by PhpStorm.
 * User: ruidi
 * Date: 16/11/21
 * Time: 下午1:47
 */


namespace Common;

class DbRedis{
  //  const PREFIX = 'rank:';
    public $redis = null;
 //   public $db;

    function __construct() {
        $this->redis = new \Redis();
        $this->redis->connect('127.0.0.1',6379);
        // $server = "localhost:8889";

    }

};
