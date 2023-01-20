<?php
/**
 * 配置功能
 */
session_start();
if (isset($_SESSION['usertype']) && $_SESSION['usertype'] == "admin" && $_SESSION['privilege'] == "0") { // 超管才可操作
    include "../include/INI.class.php";
    $ini = new INI("../app.ini");
    $ini->data['Function']['announcement'] = $_POST['announcement'];
    if(isset($_POST['allowLogin']) && $_POST['allowLogin'] == "on")
        $ini->data['Function']['SYSTEM_LOCK'] = "OFF";
    else
        $ini->data['Function']['SYSTEM_LOCK'] = "ON";
    if($ini->write())
        die("1");
    else
        die("无法操作配置文件！！");
}