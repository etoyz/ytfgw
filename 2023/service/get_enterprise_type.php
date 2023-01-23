<?php
/**
 * 获取企业账号类型
 */
session_start();
require "../include/common.php";
if (get_user_status($_SESSION['loginid']) <= 4)
    die("申报");
else
    die("评价");
