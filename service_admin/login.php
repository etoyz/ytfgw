<?php
/**
 * 管理员登录
 */
session_start();
$arr = array(
    "status" => 'notLogin'
);

require_once 'db.php';
$db = new DB();
$sql = "SELECT * FROM `user` WHERE `loginid` = '" . $db->escape($_POST['loginid']) . "' AND `loginpw` = '" . $db->escape($_POST['loginpw']) . "';";
$re = $db->query($sql);

if ($re->num_rows == 1) { // 校验成功
    $arr['status'] = 'isLogin';
    $_SESSION['usertype'] = "admin";
    $_SESSION['loginid'] = $_POST['loginid'];
    $re = $re->fetch_assoc();
    $_SESSION['privilege'] = $re['privilege'];
}

die(json_encode($arr));