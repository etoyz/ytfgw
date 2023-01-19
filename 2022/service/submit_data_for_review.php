<?php
/**
 * 提交审核（之后不可更改评判数据）
 */
session_start();
require_once "../include/common.php";
if (get_user_status($_SESSION['loginid']) <= 4)
    $dst_status = 2;
else
    $dst_status = 7;
require_once "../include/db.php";
$db = new DB();
$sql = "UPDATE `enterprise` set  `status` = $dst_status WHERE `loginid` = '" . $db->escape($_SESSION["loginid"]) . "'";
die($db->query($sql));
