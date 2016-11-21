<?php
$redis = new Redis();
$redis->connect('127.0.0.1',6379);
$redis->set('Jay13','w1111');
echo 'Jay13:'.$redis->get('Jay13');
echo '</br>';
echo 'Jay12:'.$redis->get('Jay12');
?>