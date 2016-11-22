<?php
echo 1;
$redis = new Redis();
//$redis->connect('127.0.0.1',6379);

$redis->connect('10.66.148.92',6379);
$redis->auth("crs-00dwilt7:qs654321");
$redis->set('Jay13','w1111');
echo 'Jay13:'.$redis->get('Jay13');
echo '</br>';
echo 'Jay12:'.$redis->get('Jay12');
$redis->zAdd("rank:20150401", 5, "1");
$redis->zAdd("rank:20150401", 1, "2");
$redis->zAdd("rank:20150401", 10, "3");
echo json_encode($redis->zRevRange("rank:20150401", 0, 9, true));
?>