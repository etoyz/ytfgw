<?php
/**
 * 配置功能
 */
session_start();

require "../include/common.php";

if (has_permission_admin_super()) { // 超管才可操作
    $ini = new INI("../app.ini");
    $ini->data['Function']['announcement'] = $_POST['announcement'];
    if (isset($_POST['allowLogin']) && $_POST['allowLogin'] == "on")
        $ini->data['Function']['SYSTEM_LOCK'] = "OFF";
    else
        $ini->data['Function']['SYSTEM_LOCK'] = "ON";
    if ($ini->write())
        die("1");
    else
        die("无法操作配置文件！！");
} else
    die(get_string("PERMISSION_DENY"));