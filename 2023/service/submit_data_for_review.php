<?php
/**
 * 提交审核（之后不可更改评判数据）
 */
session_start();
$response = null;
require_once "../include/common.php";
$status = get_user_status($_SESSION['loginid']);
$status %= 5;
if ($status != "1" && $status != "3") {
    if ($status == "0")
        $response = "请先完善企业信息！";
    else if ($status == "2")
        $response = "您已经提交，请勿重复提交！";
    else if ($status == "4")
        $response = "已经通过！";
    die($response);
}
if (get_user_status($_SESSION['loginid']) <= 4)
    $dst_status = 2;
else
    $dst_status = 7;
require_once "../include/db.php";
$db = new DB();
$sql = "UPDATE `enterprise` set  `status` = $dst_status WHERE `loginid` = '" . $db->escape($_SESSION["loginid"]) . "'";
die($db->query($sql));
