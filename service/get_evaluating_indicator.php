<?php
session_start();
$response = array(
    "status" => 'notLogin',
    "data" => null
);

if (isset($_SESSION['usertype'])) { // 已经登录
    require_once 'db.php';
    $sql = "DESCRIBE enterprise_data;";
    $db = new DB();
    $re = $db->query($sql);
    $response["status"] = "isLogin";
    $response["data"] = [];
    $response["code"] = 0;
    $re = $re->fetch_all(MYSQLI_ASSOC);
    foreach ($re as $f) {
        if ($f['Field'] != "loginid" && $f['Field'] != "type")
            array_push($response["data"], array(
                "indicator" => $f['Field']
            ));
    }
}

die(json_encode($response));
