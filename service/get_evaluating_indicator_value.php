<?php
session_start();
$response = array(
    "status" => 'notLogin',
    "data" => null
);

if (isset($_SESSION['usertype'])) { // 已经登录
    $user = null;
    if ($_SESSION['usertype'] === "enterprise") {
        $user = $_SESSION['loginid'];
    } else {
        $user = $_GET['user'];
    }

    require_once 'db.php';
    $sql = "DESCRIBE enterprise_data;";
    $sql2 = "SELECT * FROM enterprise_data WHERE `loginid` = $user;";
    $db = new DB();
    $re = $db->query($sql);
    $re2 = $db->query($sql2);
    $response["status"] = "isLogin";
    $response["data"] = [];
    $response["code"] = 0;
    $re = $re->fetch_all(MYSQLI_ASSOC);
//    var_dump($re2);
//    var_dump($sql2);
    $re2 = mysqli_fetch_array($re2);
    $i = 0;
    foreach ($re as $f) {
        if ($f['Field'] != "loginid")
            array_push($response["data"], array(
                "indicator" => $f['Field'],
                "value" => $re2[$i]
            ));
        $i++;
    }
}

die(json_encode($response));
