<?php
/**
 * 用户是否需要提交基本信息
 */
session_start();
if (isset($_SESSION['usertype']) && $_SESSION['usertype'] === "enterprise") {
    $status = $_SESSION['status'] % 5;
    if ($status == 0)
        die("1");
}
die("0");
