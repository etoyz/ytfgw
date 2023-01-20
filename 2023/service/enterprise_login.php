<?php
/**
 * 企业登录
 */
$arr = array(
    "status" => 'notLogin',
    "msg" => ''
);

include_once "../include/INI.class.php";
$ini = new INI("../app.ini");
$is_lock = $ini->data['Function']['SYSTEM_LOCK'];
if ($is_lock == "ON") {
    $arr['msg'] = '当前系统已锁定，如需登录，请联系管理人员！';
    die(json_encode($arr));
}

session_start();

require_once '../include/db.php';
$db = new DB();

$sql = "SELECT `loginpw`,`status` FROM `enterprise` WHERE `loginid` = '" . $db->escape($_POST['loginid']) . "';";
$re = $db->query($sql);

if ($re->num_rows == 0) { // 无此用户ID
    $arr['msg'] = '用户名错误！';
    die(json_encode($arr));
}

$data = $re->fetch_assoc();
if (password_verify($_POST['loginpw'], $data['loginpw'])) {
    $arr['status'] = 'isLogin';
    $_SESSION['usertype'] = "enterprise";
    $_SESSION['loginid'] = $_POST['loginid'];
    die(json_encode($arr));
} else {
    $arr['msg'] = '密码错误！';
    die(json_encode($arr));
}