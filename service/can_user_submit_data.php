<?php
session_start();
if (isset($_SESSION['usertype']) && $_SESSION['usertype'] === "enterprise") {
    require_once "../include/db.php";
    $db = new DB();
    $re = $db->query("SELECT `loginid` FROM `enterprise_data` WHERE `loginid` = '" . $db->escape($_SESSION['loginid']) . "'");
    if ($re->num_rows == 0)  // 首次申报 即 数据表无此企业信息
        die("1");
}
die("0");
