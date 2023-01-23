<?php
/**
 * 通过或退回企业的申请
 */
session_start();

require "../include/common.php";
require "../include/verify_login.php";
require "../include/verify_permission_admin.php";

$response = array(
    "code" => 1,
    "msg" => ""
);

if (get_user_status($_POST["loginid"]) <= 4)
    $pass_status = 4;
else
    $pass_status = 9;

$db = new DB();
if ($_POST['operation'] === "pass") {
    $sql = "UPDATE `enterprise` set `status` = $pass_status WHERE `loginid` = '" . $db->escape($_POST["loginid"]) . "';";
} else {
    $sql = "UPDATE `enterprise` set `status` = " . ($pass_status - 1) . ", `return_reason` = '" . $db->escape($_POST['return_reason']) . "' WHERE `loginid` = '" . $db->escape($_POST["loginid"]) . "';";
}
$re = $db->query($sql);
if ($re === true)
    $response['code'] = 0;
else {
    $response['code'] = 1;
    $response['msg'] = $re;
}

die(json_encode($response));