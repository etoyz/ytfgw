<?php
/**
 * 企业登录
 */
session_start(); // 开始会话

require "../include/common.php"; // 引入登录

$arr = array(
    "code" => 1,
    "msg" => ''
);

if (is_lock()) { // 检查系统是否被锁定
    $arr['msg'] = '当前系统已锁定，如需登录，请联系管理人员！';
    die(json_encode($arr)); // 锁定状态下返回信息
}

$db = new DB(); // 新建数据库实例
$sql = "SELECT `loginpw`,`status` FROM `enterprise` WHERE `loginid` = '" . $db->escape($_POST['loginid']) . "';"; // 查询数据库获取密码和状态
$re = $db->query($sql); // 执行查询语句

if ($re->num_rows == 0) { // 如果不存在该用户ID
    $arr['msg'] = '用户名错误！';
    die(json_encode($arr)); // 返回错误信息
}

$data = $re->fetch_assoc(); // 把查询结果放入$data数组
if (password_verify($_POST['loginpw'], $data['loginpw'])) { // 验证密码是否正确
    $arr['code'] = 0;
    $_SESSION['usertype'] = "enterprise"; // 定义session中的用户类型和用户ID
    $_SESSION['loginid'] = $_POST['loginid'];
    die(json_encode($arr)); // 返回成功
} else {
    $arr['msg'] = '密码错误！';
    die(json_encode($arr)); // 返回错误信息
}
