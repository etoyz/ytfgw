<?php
/**
 * 检查指定附件是否存在
 * 1：存在
 * 其他：出错
 */
session_start();

require "../include/common.php";

if (is_login()) {  // 验证登录
    $indicator = $_GET['indicator'];
    if (has_permission_admin()) // 管理员
        $loginid = $_GET['loginid'];  // 管理员可看指定人的
    else  // 非管理员
        $loginid = $_SESSION['loginid']; // 非管理员只能看自己的
    if (is_attachment_exist($loginid, $indicator)) // 若存在
        die("1");
    else  // 不存在
        die("未上传");
} else
    die(get_string("NOT_LOGIN"));
