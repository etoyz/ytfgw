<?php
session_start();
$response = array(
    "status" => 'notLogin',
    "data" => null
);

if (isset($_SESSION['usertype'])) { // 已经登录
    require_once 'db.php';
    if ($_SESSION['usertype'] == "manager") {
        $query = $_GET['query'];
        $from = ($_GET['page'] - 1) * $_GET['limit'];
        $to = $_GET['page'] * $_GET['limit'];
        $sql = "SELECT * FROM `enterprise` LIMIT " . $from . "," . $to . ";";
        $db = new DB();
        $re = $db->query($sql);
        //echo $re;
        $response["status"] = "isLogin";
        $response["data"] = $re->fetch_all(MYSQLI_ASSOC);
        $response["code"] = 0;
    } else if ($_SESSION['usertype'] == "enterprise") {
        $sql = "SELECT * FROM `enterprise` WHERE `loginid` = " . $_SESSION['loginid'] . ";";
        $db = new DB();
        $re = $db->query($sql);
        $response["status"] = "isLogin";
        $response["data"] = $re->fetch_all(MYSQLI_ASSOC);
        $response["code"] = 0;
    }
}
die(json_encode($response));