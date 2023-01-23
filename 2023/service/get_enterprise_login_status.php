<?php
session_start();

require "../include/common.php";

if (!is_login() || !is_enterprise()) // 未登录，或者登录用户不是企业
    die("0");
die("1");
