<?php
/**
 * 获取系统公告
 */
session_start();

require "../include/common.php";
require "../include/verify_login.php";

$a = (new INI("../app.ini"))->data['Function']['announcement'];
$a = str_replace(["\r\n", "\r", "\n"], '<br/>', $a);
die($a);