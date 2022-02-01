<?php
/**
 * 提交审核（之后不可更改评判数据）
 */
session_start();
if ($_SESSION['status'] <= 4)
    $dst_status = 2;
else
    $dst_status = 7;
$_SESSION['status'] = $dst_status;
require_once "db.php";
$db = new DB();
$sql = "UPDATE `enterprise` set  `status` = $dst_status WHERE `loginid` = '" . $db->escape($_SESSION["loginid"]) . "'";
die($db->query($sql));
