<?php
/**
 * 用户是否需要提交基本信息
 */
session_start();
if (isset($_SESSION['usertype']) && $_SESSION['usertype'] === "enterprise") {
    require_once "../include/common.php";
    if (get_user_status($_SESSION['loginid']) % 5 == 0)
        die("1");
}
die("0");
