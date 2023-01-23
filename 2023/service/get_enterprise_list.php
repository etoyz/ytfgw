<?php
/**
 * 获取企业列表
 */
require "../include/common.php";

session_start();
$response = array(
    "data" => [],
    "code" => 1,
    "count" => 0,
    "msg" => get_string("NOT_LOGIN")
);

if (isset($_SESSION['usertype'])) { // 已经登录
    $response["code"] = 0;
    $db = new DB();
    if ($_SESSION['usertype'] == "admin") { // 管理员用户
        if ($_SESSION['privilege'] == "0" || $_SESSION['privilege'] == "专家") // 超管或专家，地址null转为“”
            $query_address = $_GET['query_address'] ?? "";
        else // 各地区管理员，只能查看各自地区的企业
            $query_address = $_SESSION['privilege'];
        $status_specific = $_GET['status_specific'] ?? -1; // 若status为null，则用户未指定status，则查全部
        $query = $db->escape($_GET['query']);
        $start = ($_GET['page'] - 1) * $_GET['limit'];
        $limit = $_GET['limit'];
        $sql_fetch_data = null;
        $sql_fetch_cnt = null;
        if ($status_specific == '-1') { //若用户查询全部status
            if ($_GET['status'] === '0-4') { // 申报状态
                $sql_fetch_data = "SELECT * FROM `enterprise` WHERE `name` LIKE '%$query%' AND `address` LIKE '%$query_address%' AND `status` IN (0,1,2,3,4) LIMIT " . $start . "," . $limit . ";";
                $sql_fetch_cnt = "SELECT COUNT(*) AS cnt FROM `enterprise` WHERE `name` LIKE '%$query%' AND `address` LIKE '%$query_address%' AND `status` IN (0,1,2,3,4);";
            } else if ($_GET['status'] === '5-9') {// 评价状态
                $sql_fetch_data = "SELECT * FROM `enterprise` WHERE `name` LIKE '%$query%' AND `address` LIKE '%$query_address%' AND `status` IN (5,6,7,8,9) LIMIT " . $start . "," . $limit . ";";
                $sql_fetch_cnt = "SELECT COUNT(*) AS cnt FROM `enterprise` WHERE `name` LIKE '%$query%' AND `address` LIKE '%$query_address%' AND `status` IN (5,6,7,8,9);";
            }
        } else { //用户查询某特定status
            if ($_GET['status'] === '5-9') {// 评价状态
                $status_specific += 5;
            }
            $sql_fetch_data = "SELECT * FROM `enterprise` WHERE `name` LIKE '%$query%' AND `address` LIKE '%$query_address%' AND `status` = '$status_specific' LIMIT " . $start . "," . $limit . ";";
            $sql_fetch_cnt = "SELECT COUNT(*) AS cnt FROM `enterprise` WHERE `name` LIKE '%$query%' AND `address` LIKE '%$query_address%' AND `status` = '$status_specific';";
        }
        $response["data"] = $db->query($sql_fetch_data)->fetch_all(MYSQLI_ASSOC);
        $response['count'] = $db->query($sql_fetch_cnt)->fetch_assoc()['cnt'];
    } else if ($_SESSION['usertype'] == "enterprise") { // TODO : can be opt
        $sql_fetch_data = "SELECT * FROM `enterprise`;";
        $all_data = $db->query($sql_fetch_data)->fetch_all(MYSQLI_ASSOC);

        for ($i = 0; $i < count($all_data); $i++) {
            if ($all_data[$i]["loginid"] == $_SESSION['loginid']) {
                $response["data"] = [$all_data[$i]];
                break;
            }
        }
    }
}
die(json_encode($response));