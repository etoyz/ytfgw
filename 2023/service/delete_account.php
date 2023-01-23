<?php
/**
 * 删除企业账户
 */
require "../include/common.php";

$response = array(
    "code" => 1,
    "msg" => get_string("NOT_LOGIN"),
    "data" => []
);

session_start();

// 验证登录状态
if (!(isset($_SESSION['usertype']) && $_SESSION['usertype'] == "admin" && $_SESSION['privilege'] == "0")) {
    die(json_encode($response));
}

$loginid = $_POST['loginid'];

// 删除数据库中的数据
$db = new DB();
$sql = "DELETE FROM `enterprise` WHERE `loginid` = '" . $db->escape($loginid) . "'";
$re = $db->query($sql);
if ($re !== true) {
    $response['msg'] = "数据库错误：" . $re;
    die(json_encode($response));
} else {
    $response['code'] = 0;
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
