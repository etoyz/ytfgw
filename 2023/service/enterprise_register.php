<?php
/**
 * 企业注册（需要管理员权限）
 */
session_start();

require "../include/common.php";
require "../include/verify_login.php";
require "../include/verify_permission_admin.php";

$arr = array(
    "code" => 1,
    "msg" => ""
);

$db = new DB();

//名字重复
$sql = "SELECT `loginid` FROM `enterprise` WHERE `loginid` = '" . $db->escape($_POST['loginid']) . "';";
$re = $db->query($sql);
if ($re->num_rows == 1) {
    $arr['msg'] = "登录账号重复";
    die(json_encode($arr));
}

$sql = "INSERT INTO `enterprise` (loginid,loginpw,name,status,address) 
        VALUES ('" . $db->escape($_POST['loginid'])
    . "', '" . password_hash($_POST['loginpw'], PASSWORD_DEFAULT)
    . "', '" . $db->escape($_POST['name'])
    . "', '" . $db->escape($_GET['status'])
    . "', '" . $db->escape($_POST['address'])
    . "')";
$re = $db->query($sql);
if ($re === true) {
    $arr['code'] = 0;
    $arr['msg'] = "企业名【" . $_POST['name'] . "】<br/>登录账号【" . $_POST['loginid'] . "】<br/>登录密码【" . $_POST['loginpw'] . "】。";
} else {
    $arr['msg'] = $re;
}
die(json_encode($arr));