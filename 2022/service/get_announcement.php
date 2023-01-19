<?php
/**
 * 获取系统公告
 */
session_start();
include "../include/INI.class.php";

if (isset($_SESSION['loginid'])) { // 验证登录状态
    $a = (new INI("../app.ini"))->data['Function']['announcement'];
    $a = str_replace(["\r\n", "\r", "\n"], '<br>', $a);
    die($a);
}