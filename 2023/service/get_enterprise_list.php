<?php
/**
 * 获取企业列表
 */
session_start();

require "../include/common.php";
require "../include/verify_login.php";

$response = array(
    "data" => [],
    "code" => 1,
    "count" => 0,
    "msg" => ""
);

$response["code"] = 0;
$db = new DB();
if (has_permission_admin()) { // 管理员用户
    if (has_permission_admin_super() || has_permission_admin_expert()) // 超管或专家，地址null转为“”
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
} else if (is_enterprise()) { // 企业用户，只能查看自己的
    $sql_fetch_data = "SELECT * FROM `enterprise` WHERE `loginid` = '" . $_SESSION['loginid'] . "'";
    $response["data"] = [$db->query($sql_fetch_data)->fetch_assoc()];
}

die(json_encode($response));