<?php
/**
 * Created by PhpStorm.
 * User: ruidi
 * Date: 16/11/21
 * Time: 下午3:25
 */

namespace Common;


class Score
{

    public $redis = null;
    const PREFIX = 'highscore:';
    const USER_PREFIX = 'user:';

    function __construct() {
        $this->redis = new \Redis();
       // $this->redis->connect('127.0.0.1',6379);
        $this->redis->connect('10.66.148.92',6379);
        $this->redis->auth("crs-00dwilt7:qs654321");

    }


    public function setScores($obj, $scores)
    {
        $key = self::PREFIX . date('Ymd');
        $old = $this->redis->zScore($key, $obj->id);
        if (!isset($old) || $scores > $old) {

            $this->redis->zAdd($key, $scores, $obj->id);
            $this->redis->set(self::USER_PREFIX.$obj->id, json_encode($obj));
        }

    }
    private function _top10(){
        $key = self::PREFIX . date('Ymd');

        $ary =  $this->redis->zRevRange($key, 0, 9, false);
        $ret = array();
        foreach($ary as $k=>$v){
            $o = json_decode( $this->redis->get(self::USER_PREFIX.$v));
            array_push($ret, $o);
        }
        return $ret;
    }
    public function top10(){
        $ret = new \Common\Result();
        $ret->top10 = $this->_top10();
        $ret->output();
    }
    public function submit(){
        $ret = new \Common\Result();



        if(!isset($_REQUEST['score']) || !is_numeric($_REQUEST['score'])) {
            $ret->error('no score');
            $ret->output();
            return;
        }


        if(!isset($_REQUEST['id'])) {
            $ret->error('no id');
            $ret->output();
            return;
        }

        if(!isset($_REQUEST['password'])) {
            $ret->error('no password');
            $ret->output();
            return;
        }



        $pdo = new \Common\DbPdo();
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

        }else{
            $ret->error("no user");
            $ret->output();
            return;

        }



        $user = new \stdClass();
        $user->id = $id;
        $nickname = $query['nickname'];
        $score = $_REQUEST['score'];
        $user->nickname = $nickname;
        $user->score = $score;
        $this->setScores($user, $score);
        $ret->top10 = $this->_top10();
        $ret->output();


    }

}