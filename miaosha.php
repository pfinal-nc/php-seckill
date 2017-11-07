<?php
header("content-type:text/html;charset=utf-8");
$redis = new redis();
$result = $redis->connect('127.0.0.1', 7379);
$redis->watch("mywatchlist");
$len = $redis->hlen("mywatchlist");
$rob_total = 100; //抢购数量
if ($len < $rob_total) {

    $redis->multi();
    $redis->hSet("mywatchlist", "user_id_" . mt_rand(1, 999999), time());
    $rob_result = $redis->exec();
    file_put_contents("log.txt", $len . PHP_EOL, FILE_APPEND);
    if ($rob_result) {
        $mywatchlist = $redis->hGetAll("mywatchlist");
        echo "抢购成功！<br/>";
        echo "剩余数量：" . ($rob_total - $len - 1) . "<br/>";
        echo "用户列表：<pre>";
        var_dump($mywatchlist);
    } else {
        echo "手气不好，再抢购！";
        exit;
    }
} else {

    echo "已卖光！";
    exit;
}
?>