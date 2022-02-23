<?php
/**
 * 企业登录
 */
session_start();
$arr = array(
    "status" => 'notLogin',
    "msg" => ''
);

require_once '../include/db.php';
$db = new DB();

$is_lock = (new INI("../app.ini"))->data['Function']['SYSTEM_LOCK'];
if($is_lock == "ON"){
    $arr['msg'] = '当前系统已锁定，如需登录，请联系管理人员！';
    die(json_encode($arr));
}

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