<?php
/**
 * 查看附件
 */
session_start();

require "../include/common.php";

if (is_login()) {  // 验证登录
    // 构造相对路径
    $indicator = $_GET['indicator'];
    if (has_permission_admin())   // 管理员
        $loginid = $_GET['loginid'];  // 管理员可看指定人的
    else  // 非管理员
        $loginid = $_SESSION['loginid']; // 非管理员只能看自己的

    if (!is_attachment_exist($loginid, $indicator)) // 若附件不存在
        die("<h1>用户未上传！</h1><h2>应该上传的材料: $indicator</h2>");

    $url = "../uploads/$loginid/" . fetch_attachment_name($loginid, $indicator);
    // 响应头，在浏览器内查看即可
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline;');
    // 响应体
    readfile($url);
} else
    die(get_string("NOT_LOGIN"));
