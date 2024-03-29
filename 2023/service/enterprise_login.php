<?php
/**
 * 企业登录
 */
session_start();

require "../include/common.php";

$arr = array(
    "code" => 1,
    "msg" => ''
);

if (is_lock()) {
    $arr['msg'] = '当前系统已锁定，如需登录，请联系管理人员！';
    die(json_encode($arr));
}

$db = new DB();
$sql = "SELECT `loginpw`,`status` FROM `enterprise` WHERE `loginid` = '" . $db->escape($_POST['loginid']) . "';";
$re = $db->query($sql);

if ($re->num_rows == 0) { // 无此用户ID
    $arr['msg'] = '用户名错误！';
    die(json_encode($arr));
}

$data = $re->fetch_assoc();
if (password_verify($_POST['loginpw'], $data['loginpw'])) {
    $arr['code'] = 0;
    $_SESSION['usertype'] = "enterprise";
    $_SESSION['loginid'] = $_POST['loginid'];
    die(json_encode($arr));
} else {
    $arr['msg'] = '密码错误！';
    die(json_encode($arr));
}