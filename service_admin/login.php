<?php
/**
 * 管理员登录
 */
session_start(); // 开启session
$arr = array( // 定义一个数组，用于存储登录状态
    "status" => 'notLogin' // 默认为未登录状态
);

require_once 'db.php'; // 引入数据库操作类
$db = new DB(); // 实例化数据库操作类
$sql = "SELECT * FROM `user` WHERE `loginid` = '" . $db->escape($_POST['loginid']) . "' AND `loginpw` = '" . $db->escape($_POST['loginpw']) . "';"; // 构造查询语句
$re = $db->query($sql); // 执行查询语句

if ($re->num_rows == 1) { // 如果查询结果为1，说明校验成功
    $arr['status'] = 'isLogin'; // 将登录状态设置为已登录
    $_SESSION['usertype'] = "admin"; // 将用户类型设置为管理员
    $_SESSION['loginid'] = $_POST['loginid']; // 将登录id存入session
    $re = $re->fetch_assoc(); // 将查询结果转换为关联数组
    $_SESSION['privilege'] = $re['privilege']; // 将用户权限存入session
}

die(json_encode($arr)); // 输出登录状态

