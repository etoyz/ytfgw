<?php
/**
 * 用户是否需要提交基本信息
 */
session_start();

require "../include/common.php";

if (is_login() && is_enterprise()) {
    if (get_user_status($_SESSION['loginid']) % 5 == 0)
        die("1");
}
die("0");
