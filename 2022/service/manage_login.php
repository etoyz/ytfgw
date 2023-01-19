<?php
/**
 * 管理员登录
 */
session_start();
$arr = array(
    "status" => 'notLogin'
);

require_once '../include/db.php';
$db = new DB();
$sql = "SELECT * FROM `manager` WHERE `loginid` = '" . $db->escape($_POST['loginid']) . "' AND `loginpw` = '" . $db->escape($_POST['loginpw']) . "';";
$re = $db->query($sql);

if ($re->num_rows == 1) { // 后台校验成功
    $arr['status'] = 'isLogin';
    $_SESSION['usertype'] = "manager";
    $_SESSION['loginid'] = $_POST['loginid'];
    $re = $re->fetch_assoc();
    $_SESSION['privilege'] = $re['privilege'];
    $_SESSION['type'] = $re['type'];
}

die(json_encode($arr));