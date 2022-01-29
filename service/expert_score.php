<?php
session_start();

if (isset($_SESSION['loginid']) && $_SESSION['usertype'] == "manager" && $_SESSION['privilege'] == '0') {
    require_once '../include/db.php';
    $db = new DB();

    $sql = "UPDATE `enterprise` set `expert_score` = '" . $db->escape($_POST['expert_score']) . "' WHERE `loginid` = '$_POST[loginid]'";

    $re = $db->query($sql);
    if ($re === true)
        die("ok");

    die($re);
} else
    die("无权限");