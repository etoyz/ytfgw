<?php
session_start();
$response = array(
    "status" => 'notLogin',
    "data" => null,
    "code" => 0
);

if (isset($_SESSION['usertype'])) { // 已经登录
    $response["status"] = "isLogin";
    require_once 'db.php';
    $sql = null;
    $query = $_GET['query'];
    $from = ($_GET['page'] - 1) * $_GET['limit'];
    $to = $_GET['page'] * $_GET['limit'];
    if ($_GET['status'] === '-1')  // 全部
        $sql = "SELECT * FROM `enterprise` WHERE `name` LIKE '%$query%' LIMIT " . $from . "," . $to . ";";
    else if ($_GET['status'] === '0-4')  // 申报状态
        $sql = "SELECT * FROM `enterprise` WHERE `name` LIKE '%$query%' AND `status` != 5 LIMIT " . $from . "," . $to . ";";
    else if ($_GET['status'] === '5') // 评价状态
        $sql = "SELECT * FROM `enterprise` WHERE `name` LIKE '%$query%' AND `status` = 5 LIMIT " . $from . "," . $to . ";";
    $db = new DB();
    $re = $db->query($sql);
//    echo $sql;
    $all_data = $re->fetch_all(MYSQLI_ASSOC);
    if ($_SESSION['usertype'] == "manager") {
        $response["data"] = $all_data;
    } else if ($_SESSION['usertype'] == "enterprise") {
        for ($i = 0; $i < count($all_data); $i++) {
            if ($all_data[$i]["loginid"] == $_SESSION['loginid']) {
                $response["data"] = [$all_data[$i]];
                break;
            }
        }
    }
}
die(json_encode($response));