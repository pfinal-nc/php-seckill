<?php

$redis = new Redis();
$redis->connect('127.0.0.1','6379');
$uid = 1;
if($redis->get('miaosha1')) {
    if($redis->get('miaosha1') == 0) {
        echo  json_encode(['code'=>0,'msg'=>'秒杀结束']);
    } else {
        $users = $redis->LRANGE('order:1',0,10);
        foreach ($users as $k=>$v) {
            if($v==$uid) {
                echo  json_encode(['code'=>0,'msg'=>'你已经秒杀过了']);
                exit;
            }
        }
        $result = $redis->lpush('order:1',$uid);
        $redis->decr('miaosha1');
        echo  json_encode(['code'=>1,'msg'=>'秒杀成功']);
    }
} else {
    echo  json_encode(['code'=>0,'msg'=>'秒杀结束']);
}
