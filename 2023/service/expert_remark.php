<?php
/**
 * 专家给企业留言
 */
session_start();

require "../include/common.php";
require "../include/verify_login.php";
require "../include/verify_permission_admin.php";

$db = new DB();
$remark = $db->escape($_POST['remark']);
$loginid = $db->escape($_POST["loginid"]);
$sql = "UPDATE `enterprise` set `remark_expert` = '$remark' WHERE `loginid` = '$loginid';";
$re = $db->query($sql);
if ($re === true)
    die(json_encode(array(
        "code" => 0,
    )));
else
    die(json_encode(array(
        "code" => 1,
        "msg" => $re
    )));
