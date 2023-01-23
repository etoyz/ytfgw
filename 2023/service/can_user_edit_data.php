<?php
/**
 * 用户是否可以编辑评判数据
 */
session_start();

require "../include/common.php";

if (is_login() && is_enterprise()) {
    $status = get_user_status($_SESSION['loginid']) % 5;
    if ($status == 1 || $status == 3)  // 首次申报 或者 退回
        die("1");
}
die("0");
