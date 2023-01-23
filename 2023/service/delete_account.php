<?php
/**
 * 删除企业账户
 */
session_start();

require "../include/common.php";
require "../include/verify_login.php";
require "../include/verify_permission_admin_super.php";

$response = array(
    "code" => 1,
    "msg" => ""
);

$loginid = $_POST['loginid'];

// 删除数据库中的数据
$db = new DB();
$sql = "DELETE FROM `enterprise` WHERE `loginid` = '" . $db->escape($loginid) . "'";
$re = $db->query($sql);
if ($re !== true) {
    $response['msg'] = "数据库错误：" . $re;
    die(json_encode($response)); // 从数据库中删除失败，则视为操作失败
} else {
    $response['code'] = 0; // 从数据库中删除成功，则视为操作成功
    $response['msg'] = "数据库删除成功<br/>";
}

//删除上传的附件
require_once "../include/define_error_handler_to_catch_warnings.php";
try {
    recursiveRemoveDirectory("../uploads/$loginid/");
} catch (Exception $e) {
    $response['msg'] .= "附件删除失败：";
    $response['msg'] .= $e->getMessage();
}

die(json_encode($response));
