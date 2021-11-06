<?php
session_start();
$response = array(
    "status" => 'notLogin',
    "data" => null,
    "code" => 0,
    "count" => 0
);

if (isset($_SESSION['usertype'])) { // 已经登录
    $response["status"] = "isLogin";
    require_once 'db.php';
    $db = new DB();
    if ($_SESSION['usertype'] == "manager") {
        $sql = null;
        $sql2 = null;
        $query = $db->escape($_GET['query']);
        $from = ($_GET['page'] - 1) * $_GET['limit'];
        $to = $_GET['limit'];
        if ($_GET['status'] === '-1') { // 全部
            $sql = "SELECT * FROM `enterprise` WHERE `name` LIKE '%$query%' LIMIT " . $from . "," . $to . ";";
            $sql2 = "SELECT COUNT(*) AS cnt FROM `enterprise` WHERE `name` LIKE '%$query%';";
        } else if ($_GET['status'] === '0-4') { // 申报状态
            $sql = "SELECT * FROM `enterprise` WHERE `name` LIKE '%$query%' AND `status` IN (0,1,2,3,4); LIMIT " . $from . "," . $to . ";";
            $sql2 = "SELECT COUNT(*) AS cnt FROM `enterprise` WHERE `name` LIKE '%$query%' AND `status` IN (0,1,2,3,4);";
        } else if ($_GET['status'] === '5-9') {// 评价状态
            $sql = "SELECT * FROM `enterprise` WHERE `name` LIKE '%$query%' AND `status` IN (5,6,7,8,9) LIMIT " . $from . "," . $to . ";";
            $sql2 = "SELECT COUNT(*) AS cnt FROM `enterprise` WHERE `name` LIKE '%$query%' AND `status` IN (5,6,7,8,9);";
        }
        $re = $db->query($sql);
//    echo $sql;
        if ($_SESSION['usertype'] == "manager")
            $response['count'] = $db->query($sql2)->fetch_assoc()['cnt'];
        $all_data = $re->fetch_all(MYSQLI_ASSOC);
        $response["data"] = $all_data;
    } else if ($_SESSION['usertype'] == "enterprise") {
        $sql = "SELECT * FROM `enterprise`;";
        $all_data = $db->query($sql)->fetch_all(MYSQLI_ASSOC);

        for ($i = 0; $i < count($all_data); $i++) {
            if ($all_data[$i]["loginid"] == $_SESSION['loginid']) {
                $response["data"] = [$all_data[$i]];
                break;
            }
        }
    }
}
die(json_encode($response));