<?php
/**
 * 获取当前的功能配置
 */
session_start();
include "../include/common.php";
include "../include/verify_permission_admin_super.php";

$a = (new INI("../app.ini"))->data['Function'];
die(json_encode($a));