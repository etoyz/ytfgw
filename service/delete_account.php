<?php
session_start();

if (!(isset($_SESSION['usertype']) && $_SESSION['usertype'] == "manager" && $_SESSION['privilege'] == "0")) {
    die(json_encode("无权限执行此操作！"));
}

$response = array(
    "db" => false,
    "file" => false
);

$loginid = $_POST['loginid'];

// 删除数据库中的数据
require_once '../include/db.php';
$db = new DB();
$sql = "DELETE FROM `enterprise` WHERE `loginid` = '" . $db->escape($loginid) . "'";
$re = $db->query($sql);
if ($re === true)
    $response['db'] = true;
else
    $response['db'] = $re;

//删除上传的附件
require_once "../include/common.php";
require_once "define_error_handler_to_catch_warnings.php";
try {
    recursiveRemoveDirectory("../uploads/$loginid/");
    $response['file'] = true;
} catch (Exception $e) {
    $response['file'] = $e;
}

die(json_encode($response));
