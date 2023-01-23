<?php
/**
 * 用户是否可以控制系统
 */
session_start();

require "../include/common.php";

if (is_login() && has_permission_admin() && has_permission_admin_super())
    die("1");
else
    die("0");