<?php
/**
 * 通过或退回企业的申请
 */
session_start();
$response = array(
    "status" => 'notLogin',
    "data" => null
);

if (isset($_SESSION['usertype']) && $_SESSION['usertype'] === "admin") {
    $response['status'] = "isLogin";

    require_once '../include/db.php';
    $db = new DB();
    $sql = null;
    require_once "../include/common.php";

    if (get_user_status($_POST["loginid"]) <= 4)
        $pass_status = 4;
    else
        $pass_status = 9;
    if ($_POST['operation'] === "pass") {
        $sql = "UPDATE `enterprise` set `status` = $pass_status WHERE `loginid` = '" . $db->escape($_POST["loginid"]) . "';";
    } else {
        $sql = "UPDATE `enterprise` set `status` = " . ($pass_status - 1) . ", `return_reason` = '" . $db->escape($_POST['return_reason']) . "' WHERE `loginid` = '" . $db->escape($_POST["loginid"]) . "';";
    }
    $response['data'] = $db->query($sql);
}

die(json_encode($response));