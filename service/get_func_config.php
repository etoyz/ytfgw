<?php
/**
 * 获取当前的功能配置
 */
session_start();
include "../include/INI.class.php";

if (isset($_SESSION['usertype']) && $_SESSION['usertype'] == "manager" && $_SESSION['privilege'] == "0") { // 验证登录
    $a = (new INI("../app.ini"))->data['Function'];
    die(json_encode($a));
}